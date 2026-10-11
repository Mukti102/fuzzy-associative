<?php

namespace Tests\Feature;

use App\Models\CalculationDetail;
use App\Models\CalculationRun;
use App\Models\Candidate;
use App\Models\CandidateScore;
use App\Models\SelectionPeriod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeriodCandidateScoreManagementTest extends TestCase
{
    public function test_admin_can_create_and_update_selection_period()
    {
        $admin = User::factory()->admin()->create();

        // 1. Create period
        $response = $this->actingAs($admin)->post('/periods', [
            'name' => 'Gelombang 1 Santri MA 2026',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-31',
            'is_active' => '1',
            'passing_grade' => '75.50',
            'quota' => '158',
            'description' => 'Penerimaan santri baru tahun 2026',
        ]);

        $response->assertRedirect('/periods');
        $this->assertDatabaseHas('selection_periods', [
            'name' => 'Gelombang 1 Santri MA 2026',
            'academic_year' => '2026/2027',
            'is_active' => true,
        ]);

        $period = SelectionPeriod::where('name', 'Gelombang 1 Santri MA 2026')->first();

        // 2. Update period
        $response = $this->actingAs($admin)->put("/periods/{$period->id}", [
            'name' => 'Gelombang 1 Santri MA 2026 (Revisi)',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-05',
            'end_date' => '2026-04-10',
            'is_active' => '1',
            'passing_grade' => '78.00',
            'quota' => '160',
        ]);

        $response->assertRedirect('/periods');
        $this->assertDatabaseHas('selection_periods', [
            'id' => $period->id,
            'name' => 'Gelombang 1 Santri MA 2026 (Revisi)',
        ]);
    }

    public function test_period_with_candidates_cannot_be_deleted()
    {
        $admin = User::factory()->admin()->create();
        $period = SelectionPeriod::create([
            'name' => 'Periode Terisi',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-01',
            'is_active' => true,
        ]);

        Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'REG-001',
            'name' => 'Ahmad Santri',
            'gender' => 'L',
        ]);

        $response = $this->actingAs($admin)->delete("/periods/{$period->id}");
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('selection_periods', ['id' => $period->id]);
    }

    public function test_pimpinan_has_read_only_access()
    {
        $pimpinan = User::factory()->pimpinan()->create();
        $period = SelectionPeriod::create([
            'name' => 'Periode Uji Pimpinan',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-01',
            'is_active' => true,
        ]);

        // Pimpinan can view
        $this->actingAs($pimpinan)->get('/periods')->assertOk();
        $this->actingAs($pimpinan)->get("/periods/{$period->id}")->assertOk();
        $this->actingAs($pimpinan)->get('/candidates')->assertOk();

        // Pimpinan cannot mutate
        $this->actingAs($pimpinan)->post('/periods', [
            'name' => 'Illegal Period',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-01',
        ])->assertForbidden();

        $this->actingAs($pimpinan)->post('/candidates', [
            'selection_period_id' => $period->id,
            'registration_number' => 'REG-999',
            'name' => 'Illegal Candidate',
            'gender' => 'L',
        ])->assertForbidden();
    }

    public function test_candidate_registration_and_prevents_duplicate_number_per_period()
    {
        $admin = User::factory()->admin()->create();
        $period = SelectionPeriod::create([
            'name' => 'Periode Pendaftaran',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-01',
            'is_active' => true,
        ]);

        // 1. Register candidate
        $response = $this->actingAs($admin)->post('/candidates', [
            'selection_period_id' => $period->id,
            'registration_number' => 'PSB-2026-001',
            'name' => 'Fatimah Zahra',
            'gender' => 'P',
            'birth_place' => 'Kotapinang',
            'birth_date' => '2010-05-15',
            'school_origin' => 'MTs Negeri Labusel',
            'phone' => '081234567899',
            'address' => 'Jl. Pesantren No. 12',
        ]);

        $candidate = Candidate::where('registration_number', 'PSB-2026-001')->first();
        $this->assertNotNull($candidate);
        $response->assertRedirect("/candidates/{$candidate->id}");

        // Verifikasi otomatis record nilai diinisialisasi
        $this->assertDatabaseHas('candidate_scores', [
            'candidate_id' => $candidate->id,
            'is_complete' => false,
        ]);

        // 2. Prevent duplicate in same period
        $duplicateResponse = $this->actingAs($admin)->post('/candidates', [
            'selection_period_id' => $period->id,
            'registration_number' => 'PSB-2026-001',
            'name' => 'Calon Lain',
            'gender' => 'L',
        ]);

        $duplicateResponse->assertSessionHasErrors('registration_number');
    }

    public function test_score_validation_and_outdated_tracking()
    {
        $admin = User::factory()->admin()->create();
        $period = SelectionPeriod::create([
            'name' => 'Periode Nilai',
            'academic_year' => '2026/2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-01',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'selection_period_id' => $period->id,
            'registration_number' => 'REG-SC-01',
            'name' => 'Muhammad Ali',
            'gender' => 'L',
        ]);

        // 1. Nilai > 100 ditolak
        $invalidResponse = $this->actingAs($admin)->put("/scores/{$candidate->id}", [
            'academic_score' => 105,
            'btq_score' => 80,
            'tahfidz_score' => 90,
            'interview_score' => 85,
        ]);
        $invalidResponse->assertSessionHasErrors('academic_score');

        // 2. Nilai valid 0 - 100 disimpan dan is_complete = true
        $validResponse = $this->actingAs($admin)->put("/scores/{$candidate->id}", [
            'academic_score' => 85.50,
            'btq_score' => 92.00,
            'tahfidz_score' => 88.00,
            'interview_score' => 90.50,
            'notes' => 'Hafalan sangat lancar',
        ]);

        $validResponse->assertRedirect("/candidates/{$candidate->id}");
        $this->assertDatabaseHas('candidate_scores', [
            'candidate_id' => $candidate->id,
            'academic_score' => 85.50,
            'is_complete' => true,
        ]);

        // 3. Simulasikan ada riwayat perhitungan FAM lama
        $run = CalculationRun::create([
            'selection_period_id' => $period->id,
            'run_by_user_id' => $admin->id,
            'run_at' => now(),
            'total_candidates' => 1,
            'passed_candidates' => 1,
            'failed_candidates' => 0,
        ]);

        $calcDetail = CalculationDetail::create([
            'calculation_run_id' => $run->id,
            'candidate_id' => $candidate->id,
            'final_score' => 88.50,
            'decision' => 'DITERIMA',
            'rank' => 1,
            'is_outdated' => false,
        ]);

        // 4. Ubah nilai santri -> perhitungan lama harus ditandai is_outdated = true & needs_recalculation = true
        $this->actingAs($admin)->put("/scores/{$candidate->id}", [
            'academic_score' => 95.00,
            'btq_score' => 92.00,
            'tahfidz_score' => 88.00,
            'interview_score' => 90.50,
        ]);

        $this->assertDatabaseHas('candidate_scores', [
            'candidate_id' => $candidate->id,
            'academic_score' => 95.00,
            'needs_recalculation' => true,
        ]);

        $this->assertDatabaseHas('calculation_details', [
            'id' => $calcDetail->id,
            'is_outdated' => true,
        ]);
    }
}
