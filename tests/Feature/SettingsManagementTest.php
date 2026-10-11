<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SettingsManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $pimpinan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->pimpinan = User::factory()->create([
            'role' => 'pimpinan',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_settings(): void
    {
        $response = $this->get(route('settings.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_pimpinan_can_view_settings_page(): void
    {
        $response = $this->actingAs($this->pimpinan)->get(route('settings.index'));
        $response->assertOk();
        $response->assertSee('Mode Baca (Pimpinan)');
        $response->assertSee(setting('institution_name'));
    }

    public function test_pimpinan_cannot_update_settings(): void
    {
        $response = $this->actingAs($this->pimpinan)->put(route('settings.update'), [
            'institution_name' => 'Nama Pesantren Baru',
        ]);

        $response->assertForbidden();
    }

    public function test_admin_can_update_settings_successfully(): void
    {
        $response = $this->actingAs($this->admin)->put(route('settings.update'), [
            'institution_name' => 'Pesantren Modern Al-Hikmah',
            'institution_tagline' => 'Kabupaten Labuhanbatu',
            'institution_address' => 'Jl. Pendidikan No. 45',
            'institution_phone' => '081234567890',
            'institution_email' => 'admin@alhikmah.ac.id',
            'institution_website' => 'https://alhikmah.ac.id',
            'institution_letterhead_title' => 'YAYASAN AL-HIKMAH',
            'institution_letterhead_subtitle' => 'PANITIA PSB',
            'leader_name' => 'K.H. Musthafa, M.Ag.',
            'leader_title' => 'Mudir Pesantren',
            'leader_nip' => '198001012005011001',
            'committee_chairman_name' => 'Ust. Zulkifli, S.Pd.',
            'committee_chairman_title' => 'Ketua Penerimaan Santri',
        ]);

        $response->assertRedirect(route('settings.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('Pesantren Modern Al-Hikmah', AppSetting::get('institution_name'));
        $this->assertEquals('admin@alhikmah.ac.id', AppSetting::get('institution_email'));
        $this->assertEquals('K.H. Musthafa, M.Ag.', AppSetting::get('leader_name'));
    }

    public function test_admin_can_upload_and_remove_logo(): void
    {
        $file = UploadedFile::fake()->image('logo_pesantren.png', 200, 200);

        $response = $this->actingAs($this->admin)->put(route('settings.update'), [
            'institution_name' => 'Pesantren Ell Firdaus',
            'logo' => $file,
        ]);

        $response->assertRedirect(route('settings.index'));

        $logoPath = AppSetting::get('logo_path');
        $this->assertNotNull($logoPath);
        $this->assertFileExists(public_path($logoPath));

        // Test removing logo
        $responseRemove = $this->actingAs($this->admin)->put(route('settings.update'), [
            'institution_name' => 'Pesantren Ell Firdaus',
            'remove_logo' => '1',
        ]);

        $responseRemove->assertRedirect(route('settings.index'));
        $this->assertNull(AppSetting::get('logo_path'));
        $this->assertFileDoesNotExist(public_path($logoPath));
    }

    public function test_admin_can_upload_and_remove_favicon(): void
    {
        $file = UploadedFile::fake()->image('favicon.png', 64, 64);

        $response = $this->actingAs($this->admin)->put(route('settings.update'), [
            'institution_name' => 'Pesantren Ell Firdaus',
            'favicon' => $file,
        ]);

        $response->assertRedirect(route('settings.index'));

        $faviconPath = AppSetting::get('favicon_path');
        $this->assertNotNull($faviconPath);
        $this->assertFileExists(public_path($faviconPath));
        $this->assertStringContainsString($faviconPath, setting_favicon_url());

        // Test removing favicon
        $responseRemove = $this->actingAs($this->admin)->put(route('settings.update'), [
            'institution_name' => 'Pesantren Ell Firdaus',
            'remove_favicon' => '1',
        ]);

        $responseRemove->assertRedirect(route('settings.index'));
        $this->assertNull(AppSetting::get('favicon_path'));
        $this->assertFileDoesNotExist(public_path($faviconPath));
    }
}
