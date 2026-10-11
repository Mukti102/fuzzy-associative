<?php

namespace App\Http\Controllers;

use App\Models\CalculationRun;
use App\Models\SelectionPeriod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of available reports per selection period.
     */
    public function index()
    {
        $periods = SelectionPeriod::with(['calculationRuns' => fn ($q) => $q->latest('run_at')])
            ->latest()
            ->get();

        $latestRun = CalculationRun::with(['period', 'user'])->latest('run_at')->first();

        return view('reports.index', compact('periods', 'latestRun'));
    }

    /**
     * Display print-friendly official report for a specific calculation run.
     */
    public function show(CalculationRun $run)
    {
        $run->load(['period', 'user']);

        $details = $run->details()
            ->with(['candidate.score'])
            ->orderBy('rank')
            ->get();

        return view('reports.show', compact('run', 'details'));
    }
}
