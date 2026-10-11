<?php

namespace App\Http\Controllers;

use App\Models\CalculationRun;
use App\Models\Candidate;
use App\Models\CandidateScore;
use App\Models\SelectionPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the operational / summary dashboard with real database metrics.
     */
    public function index(Request $request)
    {
        // 1. Ambil periode aktif atau periode terbaru
        $activePeriod = SelectionPeriod::where('is_active', true)->first()
            ?? SelectionPeriod::latest()->first();

        // 2. Statistik berbasis database nyata
        $totalPeriods = SelectionPeriod::count();
        $totalCandidates = 0;
        $completeScoresCount = 0;
        $incompleteScoresCount = 0;
        $passedCandidates = 0;
        $failedCandidates = 0;
        $latestCalculationRun = null;
        $recentCandidates = collect();

        if ($activePeriod) {
            $totalCandidates = Candidate::where('selection_period_id', $activePeriod->id)->count();

            $completeScoresCount = CandidateScore::whereHas('candidate', function ($q) use ($activePeriod) {
                $q->where('selection_period_id', $activePeriod->id);
            })->where('is_complete', true)->count();

            $incompleteScoresCount = max(0, $totalCandidates - $completeScoresCount);

            $latestCalculationRun = CalculationRun::where('selection_period_id', $activePeriod->id)
                ->latest('run_at')
                ->first();

            if ($latestCalculationRun) {
                $passedCandidates = $latestCalculationRun->passed_candidates;
                $failedCandidates = $latestCalculationRun->failed_candidates;
            }

            $recentCandidates = Candidate::with('score')
                ->where('selection_period_id', $activePeriod->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'activePeriod',
            'totalPeriods',
            'totalCandidates',
            'completeScoresCount',
            'incompleteScoresCount',
            'passedCandidates',
            'failedCandidates',
            'latestCalculationRun',
            'recentCandidates'
        ));
    }
}
