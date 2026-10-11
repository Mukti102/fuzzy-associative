<?php

namespace Tests\Feature;

use App\Models\SelectionPeriod;
use App\Models\User;
use Tests\TestCase;

class DashboardAndNavigationTest extends TestCase
{
    public function test_unauthenticated_users_are_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/periods');
        $response->assertRedirect('/login');

        $response = $this->get('/candidates');
        $response->assertRedirect('/login');

        $response = $this->get('/scores');
        $response->assertRedirect('/login');

        $response = $this->get('/fuzzy');
        $response->assertRedirect('/login');

        $response = $this->get('/selection-results');
        $response->assertRedirect('/login');

        $response = $this->get('/reports');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_access_all_modules()
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Dashboard Operasional SPK FAM');

        $this->actingAs($admin)
            ->get('/periods')
            ->assertOk()
            ->assertSee('Periode Seleksi Santri');

        $this->actingAs($admin)
            ->get('/candidates')
            ->assertOk()
            ->assertSee('Data Calon Santri');

        $this->actingAs($admin)
            ->get('/scores')
            ->assertOk()
            ->assertSee('Penilaian 4 Kriteria');

        $this->actingAs($admin)
            ->get('/fuzzy')
            ->assertOk()
            ->assertSee('Konfigurasi Fuzzy', false);

        $this->actingAs($admin)
            ->get('/selection-results')
            ->assertOk()
            ->assertSee('Hasil Seleksi Metode FAM');

        $this->actingAs($admin)
            ->get('/reports')
            ->assertOk()
            ->assertSee('Laporan Hasil Seleksi', false);
    }

    public function test_authenticated_pimpinan_can_access_dashboard_and_reports()
    {
        $pimpinan = User::factory()->pimpinan()->create();

        $this->actingAs($pimpinan)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Dashboard Operasional SPK FAM');

        $this->actingAs($pimpinan)
            ->get('/reports')
            ->assertOk()
            ->assertSee('Laporan Hasil Seleksi', false);
    }

    public function test_inactive_user_is_logged_out_and_blocked()
    {
        $inactiveUser = User::factory()->create([
            'is_active' => false,
        ]);

        $response = $this->actingAs($inactiveUser)->get('/dashboard');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_dashboard_displays_real_metrics_and_handles_empty_state()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('Calon Santri');
        $response->assertSee('Nilai Lengkap');
        $response->assertSee('Belum Lengkap');
        $response->assertSee('Diterima');
        $response->assertSee('Tidak Diterima');
    }
}
