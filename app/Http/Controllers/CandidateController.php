<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Models\Candidate;
use App\Models\CandidateScore;
use App\Models\SelectionPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateController extends Controller
{
    /**
     * Display a listing of candidates with search, filters, and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $periodId = $request->input('period_id');
        $gender = $request->input('gender');

        $periods = SelectionPeriod::latest()->get();
        $activePeriod = SelectionPeriod::where('is_active', true)->first();

        $candidates = Candidate::with(['period', 'score'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('registration_number', 'like', "%{$search}%")
                        ->orWhere('school_origin', 'like', "%{$search}%");
                });
            })
            ->when($periodId, function ($query, $periodId) {
                $query->where('selection_period_id', $periodId);
            })
            ->when($gender, function ($query, $gender) {
                $query->where('gender', $gender);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('candidates.index', compact('candidates', 'periods', 'activePeriod', 'search', 'periodId', 'gender'));
    }

    /**
     * Show the form for creating a new candidate.
     */
    public function create()
    {
        $this->authorizeAdmin();

        $periods = SelectionPeriod::orderBy('is_active', 'desc')->latest()->get();
        if ($periods->isEmpty()) {
            return redirect()->route('periods.create')
                ->with('error', 'Silakan buat periode seleksi terlebih dahulu sebelum mendaftarkan calon santri.');
        }

        $activePeriod = SelectionPeriod::where('is_active', true)->first();

        return view('candidates.create', compact('periods', 'activePeriod'));
    }

    /**
     * Store a newly created candidate in storage.
     */
    public function store(StoreCandidateRequest $request)
    {
        $validated = $request->validated();

        $candidate = DB::transaction(function () use ($validated) {
            $candidate = Candidate::create($validated);

            // Inisialisasi record nilai 4 kriteria kosong untuk santri ini
            CandidateScore::firstOrCreate([
                'candidate_id' => $candidate->id,
            ], [
                'is_complete' => false,
                'needs_recalculation' => false,
            ]);

            return $candidate;
        });

        return redirect()->route('candidates.show', $candidate)
            ->with('success', "Calon santri '{$candidate->name}' ({$candidate->registration_number}) berhasil didaftarkan.");
    }

    /**
     * Display the specified candidate with personal details, scores, and calculation history.
     */
    public function show(Candidate $candidate)
    {
        $candidate->load(['period', 'score', 'calculationDetails.calculationRun']);

        $latestCalculationDetail = $candidate->calculationDetails()
            ->with('calculationRun')
            ->latest()
            ->first();

        return view('candidates.show', compact('candidate', 'latestCalculationDetail'));
    }

    /**
     * Show the form for editing the specified candidate.
     */
    public function edit(Candidate $candidate)
    {
        $this->authorizeAdmin();

        $periods = SelectionPeriod::latest()->get();
        return view('candidates.edit', compact('candidate', 'periods'));
    }

    /**
     * Update the specified candidate in storage.
     */
    public function update(UpdateCandidateRequest $request, Candidate $candidate)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $candidate) {
            $candidate->update($validated);
        });

        return redirect()->route('candidates.show', $candidate)
            ->with('success', "Data calon santri '{$candidate->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified candidate from storage.
     */
    public function destroy(Candidate $candidate)
    {
        $this->authorizeAdmin();

        $name = $candidate->name;

        DB::transaction(function () use ($candidate) {
            // Hapus nilai santri terlebih dahulu jika ada
            if ($candidate->score) {
                $candidate->score->delete();
            }

            // Hapus relasi detail perhitungan jika ada
            $candidate->calculationDetails()->delete();

            $candidate->delete();
        });

        return redirect()->route('candidates.index')
            ->with('success', "Data calon santri '{$name}' berhasil dihapus dari sistem.");
    }

    /**
     * Authorize admin only for mutations.
     */
    private function authorizeAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Administrator / Panitia yang berwenang melakukan tindakan ini.');
        }
    }
}
