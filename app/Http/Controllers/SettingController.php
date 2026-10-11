<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /**
     * Display the settings configuration page.
     */
    public function index()
    {
        $settings = AppSetting::getAll();

        return view('settings.index', compact('settings'));
    }

    /**
     * Update institutional and website settings.
     */
    public function update(Request $request)
    {
        // Enforce admin permission for updates
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Administrator yang memiliki wewenang untuk mengubah pengaturan website.');
        }

        $validated = $request->validate([
            'institution_name' => ['required', 'string', 'max:255'],
            'institution_tagline' => ['nullable', 'string', 'max:255'],
            'institution_address' => ['nullable', 'string', 'max:500'],
            'institution_phone' => ['nullable', 'string', 'max:100'],
            'institution_email' => ['nullable', 'email', 'max:100'],
            'institution_website' => ['nullable', 'string', 'max:255'],
            'institution_letterhead_title' => ['nullable', 'string', 'max:255'],
            'institution_letterhead_subtitle' => ['nullable', 'string', 'max:255'],
            'leader_name' => ['nullable', 'string', 'max:255'],
            'leader_title' => ['nullable', 'string', 'max:255'],
            'leader_nip' => ['nullable', 'string', 'max:100'],
            'committee_chairman_name' => ['nullable', 'string', 'max:255'],
            'committee_chairman_title' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,svg,webp,jpg,jpeg', 'max:1024'],
            'remove_favicon' => ['nullable', 'boolean'],
        ], [
            'institution_name.required' => 'Nama pesantren wajib diisi.',
            'institution_email.email' => 'Format email resmi tidak valid.',
            'logo.image' => 'File logo harus berupa gambar (PNG, JPG, WebP, SVG).',
            'logo.max' => 'Ukuran file logo maksimal 2MB.',
            'favicon.mimes' => 'Favicon harus berupa file ICO, PNG, SVG, atau WebP.',
            'favicon.max' => 'Ukuran file favicon maksimal 1MB.',
        ]);

        // Handle remove logo
        if ($request->boolean('remove_logo')) {
            $currentLogo = AppSetting::get('logo_path');
            if ($currentLogo && File::exists(public_path($currentLogo))) {
                File::delete(public_path($currentLogo));
            }
            AppSetting::set('logo_path', null, 'appearance');
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $uploadDir = public_path('uploads/settings');

            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            // Remove previous logo if exists
            $currentLogo = AppSetting::get('logo_path');
            if ($currentLogo && File::exists(public_path($currentLogo))) {
                File::delete(public_path($currentLogo));
            }

            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);

            AppSetting::set('logo_path', 'uploads/settings/' . $filename, 'appearance');
        }

        // Handle remove favicon
        if ($request->boolean('remove_favicon')) {
            $currentFavicon = AppSetting::get('favicon_path');
            if ($currentFavicon && File::exists(public_path($currentFavicon))) {
                File::delete(public_path($currentFavicon));
            }
            AppSetting::set('favicon_path', null, 'appearance');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $favFile = $request->file('favicon');
            $uploadDir = public_path('uploads/settings');

            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            $currentFavicon = AppSetting::get('favicon_path');
            if ($currentFavicon && File::exists(public_path($currentFavicon))) {
                File::delete(public_path($currentFavicon));
            }

            $favFilename = 'favicon_' . time() . '.' . $favFile->getClientOriginalExtension();
            $favFile->move($uploadDir, $favFilename);

            AppSetting::set('favicon_path', 'uploads/settings/' . $favFilename, 'appearance');
        }

        // Update textual settings
        $fields = [
            'institution_name' => 'institution',
            'institution_tagline' => 'institution',
            'institution_address' => 'institution',
            'institution_phone' => 'institution',
            'institution_email' => 'institution',
            'institution_website' => 'institution',
            'institution_letterhead_title' => 'letterhead',
            'institution_letterhead_subtitle' => 'letterhead',
            'leader_name' => 'officials',
            'leader_title' => 'officials',
            'leader_nip' => 'officials',
            'committee_chairman_name' => 'officials',
            'committee_chairman_title' => 'officials',
        ];

        foreach ($fields as $key => $group) {
            if ($request->has($key)) {
                AppSetting::set($key, $request->input($key), $group);
            }
        }

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan institusi dan website berhasil disimpan dan disinkronkan ke seluruh sistem!');
    }
}
