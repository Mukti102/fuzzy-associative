<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateScoreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FuzzyConfigController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SelectionPeriodController;
use App\Http\Controllers\SelectionResultController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified', 'role'])->group(function () {
    // 1. Dashboard Operasional
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Modul Periode Seleksi
    Route::resource('periods', SelectionPeriodController::class);
    Route::patch('periods/{period}/toggle-active', [SelectionPeriodController::class, 'toggleActive'])
        ->name('periods.toggle-active');

    // 3. Modul Calon Santri
    Route::resource('candidates', CandidateController::class);

    // 4. Modul Penilaian 4 Kriteria
    Route::get('/scores', [CandidateScoreController::class, 'index'])->name('scores.index');
    Route::get('/scores/{candidate}/edit', [CandidateScoreController::class, 'edit'])->name('scores.edit');
    Route::put('/scores/{candidate}', [CandidateScoreController::class, 'update'])->name('scores.update');

    // 5. Modul Konfigurasi Fuzzy & FAM
    Route::get('/fuzzy', [FuzzyConfigController::class, 'index'])->name('fuzzy.index');
    Route::put('/fuzzy/memberships/{membership}', [FuzzyConfigController::class, 'updateMembership'])
        ->name('fuzzy.memberships.update');
    Route::post('/fuzzy/rules', [FuzzyConfigController::class, 'storeRule'])
        ->name('fuzzy.rules.store');
    Route::patch('/fuzzy/rules/{rule}/toggle', [FuzzyConfigController::class, 'toggleRule'])
        ->name('fuzzy.rules.toggle');
    Route::delete('/fuzzy/rules/{rule}', [FuzzyConfigController::class, 'destroyRule'])
        ->name('fuzzy.rules.destroy');

    // 6. Modul Hasil Seleksi FAM
    Route::get('/selection-results', [SelectionResultController::class, 'index'])
        ->name('selection-results.index');
    Route::post('/selection-results/calculate', [SelectionResultController::class, 'calculate'])
        ->name('selection-results.calculate');
    Route::get('/selection-results/{run}', [SelectionResultController::class, 'show'])
        ->name('selection-results.show');
    Route::get('/selection-results/details/{detail}/trace', [SelectionResultController::class, 'candidateTrace'])
        ->name('selection-results.trace');

    // 7. Modul Laporan Seleksi
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{run}', [ReportController::class, 'show'])->name('reports.show');

    // 8. Modul Pengaturan Website & Lembaga
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Profil Pengguna (Breeze)
    Route::view('/profile', 'profile')->name('profile');
});

require __DIR__.'/auth.php';
