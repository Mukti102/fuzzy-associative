<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSelectionPeriodRequest;
use App\Http\Requests\UpdateSelectionPeriodRequest;
use App\Models\SelectionPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectionPeriodController extends Controller
{
    /**
     * Display a listing of selection periods.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $periods = SelectionPeriod::withCount('candidates')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('periods.index', compact('periods', 'search'));
    }

    /**
     * Show the form for creating a new selection period.
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('periods.create');
    }

    /**
     * Store a newly created selection period in storage.
     */
    public function store(StoreSelectionPeriodRequest $request)
    {
        $validated = $request->validated();
        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($validated, $isActive) {
            // Jika periode baru ditandai aktif, nonaktifkan periode lain
            if ($isActive) {
                SelectionPeriod::where('is_active', true)->update(['is_active' => false]);
            }

            $validated['is_active'] = $isActive;
            SelectionPeriod::create($validated);
        });

        return redirect()->route('periods.index')
            ->with('success', 'Periode seleksi baru berhasil ditambahkan.');
    }

    /**
     * Display the specified selection period.
     */
    public function show(SelectionPeriod $period)
    {
        $period->loadCount('candidates');
        $candidates = $period->candidates()
            ->with('score')
            ->latest()
            ->paginate(15);

        return view('periods.show', compact('period', 'candidates'));
    }

    /**
     * Show the form for editing the specified selection period.
     */
    public function edit(SelectionPeriod $period)
    {
        $this->authorizeAdmin();
        return view('periods.edit', compact('period'));
    }

    /**
     * Update the specified selection period in storage.
     */
    public function update(UpdateSelectionPeriodRequest $request, SelectionPeriod $period)
    {
        $validated = $request->validated();
        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($validated, $isActive, $period) {
            if ($isActive && !$period->is_active) {
                SelectionPeriod::where('id', '!=', $period->id)->update(['is_active' => false]);
            }

            $validated['is_active'] = $isActive;
            $period->update($validated);
        });

        return redirect()->route('periods.index')
            ->with('success', "Periode seleksi '{$period->name}' berhasil diperbarui.");
    }

    /**
     * Toggle active status of a selection period.
     */
    public function toggleActive(SelectionPeriod $period)
    {
        $this->authorizeAdmin();

        DB::transaction(function () use ($period) {
            if (!$period->is_active) {
                // Aktifkan periode ini dan nonaktifkan yang lain
                SelectionPeriod::where('id', '!=', $period->id)->update(['is_active' => false]);
                $period->update(['is_active' => true]);
            } else {
                $period->update(['is_active' => false]);
            }
        });

        $statusText = $period->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Periode seleksi '{$period->name}' berhasil {$statusText}.");
    }

    /**
     * Remove the specified selection period from storage.
     */
    public function destroy(SelectionPeriod $period)
    {
        $this->authorizeAdmin();

        // Validasi perlindungan data relasi: Jangan hapus jika sudah memiliki data santri
        if ($period->candidates()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus periode ini karena sudah memiliki data calon santri terdaftar.');
        }

        if ($period->calculationRuns()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus periode ini karena memiliki riwayat komputasi seleksi.');
        }

        DB::transaction(function () use ($period) {
            $period->delete();
        });

        return redirect()->route('periods.index')
            ->with('success', "Periode seleksi '{$period->name}' berhasil dihapus.");
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
