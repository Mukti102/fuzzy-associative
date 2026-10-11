<?php

namespace Tests\Feature;

use App\Models\CalculationDetail;
use App\Models\CalculationRun;
use App\Models\Candidate;
use App\Models\CandidateScore;
use App\Models\SelectionPeriod;
use App\Models\User;
use Database\Seeders\FuzzyParameterSeeder;
use Tests\TestCase;

class FAMCalculationEngineTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Seed parameter dan aturan default Kosko FAM
        $this->seed(FuzzyParameterSeeder::class);
    }

    public function test_incomplete_scores_are_rejected_with_clear_error_message()
    {
        $admin = User::factory()->admin()->create();
        $period = SelectionPeriod::create([
            'name' => 'Gelombang Uji Nilai Belum Lengkap',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-31',
            'is_active' => true,
            'passing_grade' => 70.0,
        ]);

        // Calon 1: nilai lengkap
        $c1 = Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'REG-INC-01',
            'name' => 'Santri Lengkap',
            'gender' => 'L',
        ]);
        $c1->score->update([
            'academic_score' => 85,
            'btq_score' => 80,
            'tahfidz_score' => 90,
            'interview_score' => 85,
            'is_complete' => true,
        ]);

        // Calon 2: nilai belum lengkap
        $c2 = Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'REG-INC-02',
            'name' => 'Santri Belum Lengkap',
            'gender' => 'P',
        ]);
        // is_complete default false

        $response = $this->actingAs($admin)->post('/selection-results/calculate', [
            'selection_period_id' => $period->id,
            'encoding_method' => 'correlation-minimum',
            'composition_method' => 'max-min',
            'defuzzification_method' => 'weighted-average',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('calculation_runs', [
            'selection_period_id' => $period->id,
        ]);
    }

    public function test_admin_can_successfully_execute_fam_calculation_and_view_results()
    {
        $admin = User::factory()->admin()->create();
        $period = SelectionPeriod::create([
            'name' => 'Gelombang PSB MA 2026',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-31',
            'is_active' => true,
            'passing_grade' => 70.0,
        ]);

        // Calon 1: Nilai Tinggi
        $c1 = Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'PSB-001',
            'name' => 'Zaid bin Tsabit',
            'gender' => 'L',
        ]);
        $c1->score->update([
            'academic_score' => 90,
            'btq_score' => 95,
            'tahfidz_score' => 95,
            'interview_score' => 90,
            'is_complete' => true,
        ]);

        // Calon 2: Nilai Sedang
        $c2 = Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'PSB-002',
            'name' => 'Fatimah Az-Zahra',
            'gender' => 'P',
        ]);
        $c2->score->update([
            'academic_score' => 75,
            'btq_score' => 78,
            'tahfidz_score' => 80,
            'interview_score' => 75,
            'is_complete' => true,
        ]);

        // Calon 3: Nilai Rendah
        $c3 = Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'PSB-003',
            'name' => 'Umar Santri',
            'gender' => 'L',
        ]);
        $c3->score->update([
            'academic_score' => 45,
            'btq_score' => 50,
            'tahfidz_score' => 40,
            'interview_score' => 50,
            'is_complete' => true,
        ]);

        $response = $this->actingAs($admin)->post('/selection-results/calculate', [
            'selection_period_id' => $period->id,
            'encoding_method' => 'correlation-minimum',
            'composition_method' => 'max-min',
            'defuzzification_method' => 'weighted-average',
        ]);

        $this->assertDatabaseHas('calculation_runs', [
            'selection_period_id' => $period->id,
            'total_candidates' => 3,
        ]);

        $run = CalculationRun::where('selection_period_id', $period->id)->latest()->first();
        $this->assertNotNull($run);
        $response->assertRedirect(route('selection-results.show', $run));

        // Verifikasi detail perankingan
        $details = $run->details()->orderBy('rank')->get();
        $this->assertCount(3, $details);

        // Rank 1 harus Zaid bin Tsabit
        $this->assertEquals($c1->id, $details[0]->candidate_id);
        $this->assertEquals(1, $details[0]->rank);
        $this->assertEquals('DITERIMA', $details[0]->decision);
        $this->assertGreaterThanOrEqual(70.0, (float) $details[0]->final_score);

        // Verifikasi halaman show dan trace dapat diakses
        $showResponse = $this->actingAs($admin)->get(route('selection-results.show', $run));
        $showResponse->assertOk();
        $showResponse->assertSee('Zaid bin Tsabit');
        $showResponse->assertSee('DITERIMA');

        $traceResponse = $this->actingAs($admin)->get(route('selection-results.trace', $details[0]));
        $traceResponse->assertOk();
        $traceResponse->assertSee('Tahap 1: Fuzzifikasi');
        $traceResponse->assertSee('Tahap 2: Evaluasi Aturan');
        $traceResponse->assertSee('Tahap 3: Agregasi Komposisi');
        $traceResponse->assertSee('Tahap 4: Defuzzifikasi');
    }

    public function test_tie_breaker_hierarchy_resolves_identical_defuzzified_scores()
    {
        $admin = User::factory()->admin()->create();
        $period = SelectionPeriod::create([
            'name' => 'Gelombang Tie Breaker',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-31',
            'is_active' => true,
            'passing_grade' => 70.0,
        ]);

        // Calon A & Calon B memiliki skor identik di defuzzifikasi (misal nilai sama persis kecuali Tahfidz)
        // Kita beri skor yang sama persis sehingga menghasilkan skor defuzzifikasi sama,
        // namun Calon A memiliki tahfidz lebih tinggi
        $cA = Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'REG-TIE-A',
            'name' => 'Kandidat Tahfidz Unggul',
            'gender' => 'L',
        ]);
        $cA->score->update([
            'academic_score' => 90,
            'btq_score' => 90,
            'tahfidz_score' => 95,
            'interview_score' => 90,
            'is_complete' => true,
        ]);

        $cB = Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'REG-TIE-B',
            'name' => 'Kandidat Tahfidz Standar',
            'gender' => 'P',
        ]);
        $cB->score->update([
            'academic_score' => 90,
            'btq_score' => 90,
            'tahfidz_score' => 90,
            'interview_score' => 90,
            'is_complete' => true,
        ]);

        $this->actingAs($admin)->post('/selection-results/calculate', [
            'selection_period_id' => $period->id,
            'encoding_method' => 'correlation-minimum',
            'composition_method' => 'max-min',
            'defuzzification_method' => 'weighted-average',
        ]);

        $run = CalculationRun::where('selection_period_id', $period->id)->first();
        $details = $run->details()->orderBy('rank')->get();

        // Calon A dengan tahfidz 95 harus rank 1
        $this->assertEquals($cA->id, $details[0]->candidate_id);
        $this->assertEquals(1, $details[0]->rank);
        $this->assertEquals($cB->id, $details[1]->candidate_id);
        $this->assertEquals(2, $details[1]->rank);
    }

    public function test_pimpinan_has_read_only_access_to_results_and_reports()
    {
        $pimpinan = User::factory()->pimpinan()->create();
        $admin = User::factory()->admin()->create();

        $period = SelectionPeriod::create([
            'name' => 'Periode Akses Pimpinan',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-31',
            'is_active' => true,
            'passing_grade' => 70.0,
        ]);

        $c = Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'REG-PIM-01',
            'name' => 'Santri Pimpinan',
            'gender' => 'L',
        ]);
        $c->score->update([
            'academic_score' => 80,
            'btq_score' => 85,
            'tahfidz_score' => 85,
            'interview_score' => 80,
            'is_complete' => true,
        ]);

        // Pimpinan cannot execute calculation
        $this->actingAs($pimpinan)->post('/selection-results/calculate', [
            'selection_period_id' => $period->id,
            'encoding_method' => 'correlation-minimum',
            'composition_method' => 'max-min',
            'defuzzification_method' => 'weighted-average',
        ])->assertForbidden();

        // Admin executes calculation
        $this->actingAs($admin)->post('/selection-results/calculate', [
            'selection_period_id' => $period->id,
            'encoding_method' => 'correlation-minimum',
            'composition_method' => 'max-min',
            'defuzzification_method' => 'weighted-average',
        ]);

        $run = CalculationRun::where('selection_period_id', $period->id)->first();

        // Pimpinan CAN view results, trace, and reports
        $this->actingAs($pimpinan)->get('/selection-results')->assertOk();
        $this->actingAs($pimpinan)->get(route('selection-results.show', $run))->assertOk();
        $this->actingAs($pimpinan)->get('/reports')->assertOk();
        $this->actingAs($pimpinan)->get(route('reports.show', $run))->assertOk();
    }
}
