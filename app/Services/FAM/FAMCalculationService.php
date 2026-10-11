<?php

namespace App\Services\FAM;

use App\Models\CalculationDetail;
use App\Models\CalculationRun;
use App\Models\Candidate;
use App\Models\CandidateScore;
use App\Models\SelectionPeriod;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FAMCalculationService
{
    public function __construct(
        protected FuzzificationService $fuzzificationService,
        protected FAMMatrixService $matrixService,
        protected InferenceService $inferenceService,
        protected DefuzzificationService $defuzzificationService
    ) {}

    /**
     * Run complete FAM selection calculation for a selection period.
     *
     * @param SelectionPeriod $period
     * @param User $executedByUser
     * @param string $encodingMethod
     * @param string $compositionMethod
     * @param string $defuzzificationMethod
     * @return CalculationRun
     */
    public function execute(
        SelectionPeriod $period,
        User $executedByUser,
        string $encodingMethod = 'correlation-minimum',
        string $compositionMethod = 'max-min',
        string $defuzzificationMethod = 'weighted-average'
    ): CalculationRun {
        // 1. Ambil calon santri pada periode ini
        $candidates = Candidate::with('score')
            ->where('selection_period_id', $period->id)
            ->get();

        if ($candidates->isEmpty()) {
            throw new InvalidArgumentException('Tidak ada calon santri yang terdaftar pada periode seleksi ini.');
        }

        // 2. Validasi kelengkapan nilai: Tolak proses jika ada santri yang nilainya belum lengkap
        $incompleteCandidates = $candidates->filter(function ($candidate) {
            return !$candidate->score || !$candidate->score->is_complete;
        });

        if ($incompleteCandidates->isNotEmpty()) {
            $names = $incompleteCandidates->take(3)->pluck('name')->implode(', ');
            $count = $incompleteCandidates->count();
            throw new InvalidArgumentException(
                "Proses seleksi ditolak karena terdapat {$count} calon santri dengan nilai belum lengkap ({$names}...). Seluruh 4 kriteria wajib diisi sebelum kalkulasi dijalankan."
            );
        }

        $passingGrade = $period->passing_grade !== null ? (float) $period->passing_grade : 70.0;

        // 3. Lakukan kalkulasi FAM per calon santri
        $evaluatedCandidates = [];

        foreach ($candidates as $candidate) {
            $scores = [
                'AKADEMIK' => (float) $candidate->score->academic_score,
                'BTQ' => (float) $candidate->score->btq_score,
                'TAHFIDZ' => (float) $candidate->score->tahfidz_score,
                'WAWANCARA' => (float) $candidate->score->interview_score,
            ];

            // A. Fuzzifikasi
            $fuzzificationData = $this->fuzzificationService->fuzzifyAll($scores);

            // B. Evaluasi Aturan & Matriks FAM
            $evaluatedRules = $this->matrixService->evaluateRules($fuzzificationData, $encodingMethod);

            // C. Inferensi & Komposisi
            $inferenceData = $this->inferenceService->aggregate($evaluatedRules, $compositionMethod);

            // D. Defuzzifikasi
            $defuzzificationData = $this->defuzzificationService->defuzzify(
                $evaluatedRules,
                $inferenceData,
                $defuzzificationMethod
            );

            $finalScore = $defuzzificationData['crisp_value'];
            $decision = $finalScore >= $passingGrade ? 'DITERIMA' : 'TIDAK DITERIMA';

            $evaluatedCandidates[] = [
                'candidate' => $candidate,
                'scores' => $scores,
                'fuzzification_data' => $fuzzificationData,
                'activated_rules_data' => $evaluatedRules,
                'fam_matrix_data' => [
                    'encoding' => $encodingMethod,
                    'active_count' => $inferenceData['total_active_rules'],
                ],
                'composition_data' => $inferenceData,
                'defuzzification_data' => $defuzzificationData,
                'final_score' => $finalScore,
                'decision' => $decision,
            ];
        }

        // 4. Pemeringkatan (Ranking) Konsisten
        // Urutkan nilai akhir desc. Tie-breaker: Tahfidz desc, BTQ desc, Akademik desc, Wawancara desc
        usort($evaluatedCandidates, function ($a, $b) {
            if ($b['final_score'] != $a['final_score']) {
                return $b['final_score'] <=> $a['final_score'];
            }
            if ($b['scores']['TAHFIDZ'] != $a['scores']['TAHFIDZ']) {
                return $b['scores']['TAHFIDZ'] <=> $a['scores']['TAHFIDZ'];
            }
            if ($b['scores']['BTQ'] != $a['scores']['BTQ']) {
                return $b['scores']['BTQ'] <=> $a['scores']['BTQ'];
            }
            if ($b['scores']['AKADEMIK'] != $a['scores']['AKADEMIK']) {
                return $b['scores']['AKADEMIK'] <=> $a['scores']['AKADEMIK'];
            }
            return $b['scores']['WAWANCARA'] <=> $a['scores']['WAWANCARA'];
        });

        // 5. Simpan ke database dalam Database Transaction
        return DB::transaction(function () use (
            $period,
            $executedByUser,
            $encodingMethod,
            $compositionMethod,
            $defuzzificationMethod,
            $evaluatedCandidates,
            $passingGrade
        ) {
            $total = count($evaluatedCandidates);
            $passed = 0;
            $failed = 0;

            foreach ($evaluatedCandidates as $item) {
                if ($item['decision'] === 'DITERIMA') {
                    $passed++;
                } else {
                    $failed++;
                }
            }

            // A. Simpan calculation_runs
            $run = CalculationRun::create([
                'selection_period_id' => $period->id,
                'run_by_user_id' => $executedByUser->id,
                'run_at' => now(),
                'encoding_method' => $encodingMethod,
                'composition_method' => $compositionMethod,
                'defuzzification_method' => $defuzzificationMethod,
                'config_snapshot' => [
                    'passing_grade' => $passingGrade,
                    'period_name' => $period->name,
                    'academic_year' => $period->academic_year,
                    'encoding' => $encodingMethod,
                    'composition' => $compositionMethod,
                    'defuzzification' => $defuzzificationMethod,
                ],
                'total_candidates' => $total,
                'passed_candidates' => $passed,
                'failed_candidates' => $failed,
            ]);

            // B. Simpan calculation_details dengan peringkat
            $rank = 1;
            foreach ($evaluatedCandidates as $item) {
                CalculationDetail::create([
                    'calculation_run_id' => $run->id,
                    'candidate_id' => $item['candidate']->id,
                    'fuzzification_data' => $item['fuzzification_data'],
                    'activated_rules_data' => $item['activated_rules_data'],
                    'fam_matrix_data' => $item['fam_matrix_data'],
                    'composition_data' => $item['composition_data'],
                    'defuzzification_data' => $item['defuzzification_data'],
                    'final_score' => $item['final_score'],
                    'decision' => $item['decision'],
                    'rank' => $rank++,
                    'is_outdated' => false,
                ]);

                // Reset penanda kebutuhan kalkulasi ulang santri
                $item['candidate']->score->update([
                    'needs_recalculation' => false,
                ]);
            }

            return $run;
        });
    }
}
