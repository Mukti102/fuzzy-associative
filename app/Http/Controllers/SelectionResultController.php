<?php

namespace App\Http\Controllers;

use App\Models\CalculationDetail;
use App\Models\CalculationRun;
use App\Models\Candidate;
use App\Models\SelectionPeriod;
use App\Services\FAM\FAMCalculationService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class SelectionResultController extends Controller
{
    public function __construct(
        protected FAMCalculationService $calculationService
    ) {}

    /**
     * Display a listing of FAM calculation runs.
     */
    public function index(Request $request)
    {
        $periodId = $request->input('period_id');

        $periods = SelectionPeriod::latest()->get();
        $activePeriod = SelectionPeriod::where('is_active', true)->first();

        $runs = CalculationRun::with(['period', 'user'])
            ->when($periodId, fn ($q) => $q->where('selection_period_id', $periodId))
            ->latest('run_at')
            ->paginate(10);

        // Hitung santri siap hitung pada periode aktif
        $readyCandidatesCount = 0;
        $totalCandidatesCount = 0;
        if ($activePeriod) {
            $totalCandidatesCount = Candidate::where('selection_period_id', $activePeriod->id)->count();
            $readyCandidatesCount = Candidate::where('selection_period_id', $activePeriod->id)
                ->whereHas('score', fn ($q) => $q->where('is_complete', true))
                ->count();
        }

        return view('selection-results.index', compact(
            'runs',
            'periods',
            'activePeriod',
            'periodId',
            'readyCandidatesCount',
            'totalCandidatesCount'
        ));
    }

    /**
     * Trigger and execute the FAM selection calculation.
     */
    public function calculate(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'selection_period_id' => ['required', 'exists:selection_periods,id'],
            'encoding_method' => ['required', 'in:correlation-minimum,correlation-product'],
            'composition_method' => ['required', 'in:max-min,max-product'],
            'defuzzification_method' => ['required', 'in:weighted-average,winner-take-all,centroid'],
        ]);

        $period = SelectionPeriod::findOrFail($validated['selection_period_id']);

        try {
            $run = $this->calculationService->execute(
                $period,
                auth()->user(),
                $validated['encoding_method'],
                $validated['composition_method'],
                $validated['defuzzification_method']
            );

            return redirect()->route('selection-results.show', $run)
                ->with('success', "Perhitungan seleksi FAM berhasil dijalankan. Total {$run->total_candidates} calon santri telah diproses.");
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified calculation run results with rankings and decision badges.
     */
    public function show(Request $request, CalculationRun $run)
    {
        $run->load(['period', 'user']);

        $decisionFilter = $request->input('decision'); // 'DITERIMA', 'TIDAK DITERIMA'
        $genderFilter = $request->input('gender');

        $details = $run->details()
            ->with(['candidate.score'])
            ->when($decisionFilter, fn ($q) => $q->where('decision', $decisionFilter))
            ->when($genderFilter, fn ($q) => $q->whereHas('candidate', fn ($cq) => $cq->where('gender', $genderFilter)))
            ->paginate(20)
            ->withQueryString();

        return view('selection-results.show', compact('run', 'details', 'decisionFilter', 'genderFilter'));
    }

    /**
     * Step-by-step mathematical trace modal / view for a single candidate.
     */
    public function candidateTrace(CalculationDetail $detail)
    {
        $detail->load(['calculationRun.period', 'candidate.score']);

        return view('selection-results.trace', compact('detail'));
    }

    private function authorizeAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Administrator / Panitia yang berwenang menjalankan proses perhitungan seleksi.');
        }
    }
}
