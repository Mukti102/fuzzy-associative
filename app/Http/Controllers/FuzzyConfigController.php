<?php

namespace App\Http\Controllers;

use App\Models\FamRule;
use App\Models\FuzzyMembership;
use App\Models\FuzzyVariable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuzzyConfigController extends Controller
{
    /**
     * Display fuzzy variables, memberships, and FAM rules.
     */
    public function index()
    {
        $inputVariables = FuzzyVariable::where('type', 'input')
            ->with('memberships')
            ->get();

        $outputVariable = FuzzyVariable::where('type', 'output')
            ->with('memberships')
            ->first();

        $rules = FamRule::with([
            'academicMembership',
            'btqMembership',
            'tahfidzMembership',
            'interviewMembership',
            'outputMembership',
        ])
        ->get();

        return view('fuzzy.index', compact('inputVariables', 'outputVariable', 'rules'));
    }

    /**
     * Update membership curve parameters (a, b, c, d).
     */
    public function updateMembership(Request $request, FuzzyMembership $membership)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'param_a' => ['required', 'numeric', 'min:0', 'max:100'],
            'param_b' => ['required', 'numeric', 'min:0', 'max:100'],
            'param_c' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'param_d' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $membership->update($validated);

        return redirect()->route('fuzzy.index')
            ->with('success', "Parameter fungsi keanggotaan '{$membership->name}' berhasil diperbarui.");
    }

    /**
     * Store a new FAM association rule.
     */
    public function storeRule(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:fam_rules,code'],
            'academic_membership_id' => ['required', 'exists:fuzzy_memberships,id'],
            'btq_membership_id' => ['required', 'exists:fuzzy_memberships,id'],
            'tahfidz_membership_id' => ['required', 'exists:fuzzy_memberships,id'],
            'interview_membership_id' => ['required', 'exists:fuzzy_memberships,id'],
            'output_membership_id' => ['required', 'exists:fuzzy_memberships,id'],
            'weight' => ['required', 'numeric', 'min:0.1', 'max:1.0'],
        ]);

        $validated['is_active'] = true;

        FamRule::create($validated);

        return redirect()->route('fuzzy.index')
            ->with('success', "Aturan FAM {$validated['code']} berhasil ditambahkan.");
    }

    /**
     * Toggle rule active status.
     */
    public function toggleRule(FamRule $rule)
    {
        $this->authorizeAdmin();

        $rule->update(['is_active' => !$rule->is_active]);

        $status = $rule->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Aturan FAM {$rule->code} berhasil {$status}.");
    }

    /**
     * Delete an existing FAM rule.
     */
    public function destroyRule(FamRule $rule)
    {
        $this->authorizeAdmin();

        $code = $rule->code;
        $rule->delete();

        return redirect()->route('fuzzy.index')
            ->with('success', "Aturan FAM {$code} berhasil dihapus.");
    }

    private function authorizeAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Administrator / Panitia yang berwenang mengubah konfigurasi FAM.');
        }
    }
}
