<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCandidateScoreRequest;
use App\Models\CalculationDetail;
use App\Models\Candidate;
use App\Models\CandidateScore;
use App\Models\SelectionPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateScoreController extends Controller
{
    /**
     * Display a listing of candidates with their score evaluation status.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $periodId = $request->input('period_id');
        $status = $request->input('status'); // 'all', 'lengkap', 'belum_lengkap'

        $periods = SelectionPeriod::latest()->get();
        $activePeriod = SelectionPeriod::where('is_active', true)->first();

        $candidates = Candidate::with(['period', 'score'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('registration_number', 'like', "%{$search}%");
                });
            })
            ->when($periodId, function ($query, $periodId) {
                $query->where('selection_period_id', $periodId);
            })
            ->when($status === 'lengkap', function ($query) {
                $query->whereHas('score', fn ($q) => $q->where('is_complete', true));
            })
            ->when($status === 'belum_lengkap', function ($query) {
                $query->where(function ($q) {
                    $q->whereDoesntHave('score')
                        ->orWhereHas('score', fn ($sq) => $sq->where('is_complete', false));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('scores.index', compact('candidates', 'periods', 'activePeriod', 'search', 'periodId', 'status'));
    }

    /**
     * Show the form for editing scores of a specific candidate.
     */
    public function edit(Candidate $candidate)
    {
        $this->authorizeAdmin();

        $candidate->load(['period', 'score']);
        $score = $candidate->score ?? new CandidateScore(['candidate_id' => $candidate->id]);

        return view('scores.edit', compact('candidate', 'score'));
    }

    /**
     * Update the candidate's scores in storage.
     */
    public function update(UpdateCandidateScoreRequest $request, Candidate $candidate)
    {
        $this->authorizeAdmin();

        $validated = $request->validated();

        DB::transaction(function () use ($candidate, $validated) {
            $score = CandidateScore::firstOrNew(['candidate_id' => $candidate->id]);

            // Cek apakah nilai mengalami perubahan dari nilai sebelumnya
            $hasScoreChanged = (
                $score->exists && (
                    $score->academic_score != $validated['academic_score'] ||
                    $score->btq_score != $validated['btq_score'] ||
                    $score->tahfidz_score != $validated['tahfidz_score'] ||
                    $score->interview_score != $validated['interview_score']
                )
            );

            $score->fill($validated);
            $score->is_complete = $score->checkCompletion();

            if ($hasScoreChanged) {
                // Tandai bahwa nilai santri ini butuh dihitung ulang
                $score->needs_recalculation = true;

                // Tandai seluruh riwayat perhitungan lama calon santri ini sebagai tidak mutakhir (outdated)
                CalculationDetail::where('candidate_id', $candidate->id)
                    ->update(['is_outdated' => true]);
            }

            $score->save();
        });

        return redirect()->route('candidates.show', $candidate)
            ->with('success', "Penilaian calon santri '{$candidate->name}' berhasil disimpan.");
    }

    /**
     * Authorize admin only for editing scores.
     */
    private function authorizeAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Administrator / Panitia yang berwenang memasukkan atau mengubah nilai seleksi.');
        }
    }
}
