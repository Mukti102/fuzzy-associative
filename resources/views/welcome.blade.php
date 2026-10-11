<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPK Penerimaan Santri Baru - {{ setting('institution_name', 'Pesantren Ell Firdaus') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ setting_favicon_url() }}" type="{{ str_ends_with(setting_favicon_url(), '.svg') ? 'image/svg+xml' : 'image/x-icon' }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col justify-between font-sans text-slate-800 antialiased selection:bg-emerald-600 selection:text-white">

    <!-- Header / Navbar -->
    <header class="border-b border-slate-200/80 bg-white/95 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if (setting_logo_url())
                    <img src="{{ setting_logo_url() }}" alt="Logo" class="w-9 h-9 rounded-lg object-contain shrink-0 shadow-sm">
                @else
                    <div class="w-9 h-9 rounded-lg bg-emerald-800 text-white flex items-center justify-center font-bold text-base shadow-sm shrink-0">
                        {{ strtoupper(substr(setting('institution_name', 'EF'), 0, 2)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-sm font-bold text-slate-900 tracking-tight leading-none">{{ setting('institution_name', 'Pesantren Ell Firdaus') }}</h1>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ setting('institution_tagline', 'Kabupaten Labuhanbatu Selatan, Sumatera Utara') }}</p>
                </div>
            </div>

            @if (Route::has('login'))
                <livewire:welcome.navigation />
            @endif
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        <!-- Hero Section -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-8 sm:p-10 shadow-sm mb-8">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/80 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    Sistem Pendukung Keputusan Seleksi Masuk
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight leading-snug">
                    Penerimaan Santri Baru Menggunakan Metode <span class="text-emerald-800 underline decoration-emerald-300 underline-offset-4">Fuzzy Associative Memory (FAM)</span>
                </h2>
                <p class="mt-3 text-sm text-slate-600 leading-relaxed max-w-2xl">
                    Platform pengolahan keputusan seleksi santri berbasis kecerdasan buatan fuzzy associational memory (Kosko) yang mengintegrasikan nilai akademik, kefasihan tilawah, ketahanan hafalan, serta hasil wawancara calon santri secara transparan dan terukur.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                            <span>Masuk ke Dashboard Utama</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                            <span>Masuk ke Portal Seleksi</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endauth
                    <a href="#kriteria" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-sm transition">
                        Pelajari Metode & Kriteria
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Kriteria Seleksi -->
        <div id="kriteria" class="mb-8">
            <div class="mb-4">
                <h3 class="text-base font-bold text-slate-900 tracking-tight">4 Pilar Kriteria Penilaian Santri</h3>
                <p class="text-xs text-slate-500">Bobot derajat keanggotaan fuzzy dimodelkan lewat kurva segitiga & trapesium</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- K1 -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm hover:border-slate-300 transition">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold font-mono text-xs text-slate-700 mb-3">
                        K1
                    </div>
                    <h4 class="text-xs font-bold text-slate-900 mb-1">Nilai Akademik / Rapor</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Akumulasi rata-rata rapor mata pelajaran pokok (Matematika, IPA, Bahasa Indonesia, & Bahasa Inggris) dari jenjang pendidikan asal.
                    </p>
                </div>

                <!-- K2 -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm hover:border-slate-300 transition">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold font-mono text-xs mb-3">
                        K2
                    </div>
                    <h4 class="text-xs font-bold text-slate-900 mb-1">Baca Tulis Al-Qur'an (BTQ)</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Penguasaan kaidah tajwid, makharijul huruf, tartil pembacaan ayat, serta kemampuan imla' khat penulisan ayat suci Al-Qur'an.
                    </p>
                </div>

                <!-- K3 -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm hover:border-slate-300 transition">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold font-mono text-xs mb-3">
                        K3
                    </div>
                    <h4 class="text-xs font-bold text-slate-900 mb-1">Hafalan Al-Qur'an (Tahfidz)</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Evaluasi hafalan juz/surah, kelancaran sambung ayat acak, ketahanan mutqin, dan komitmen target ziyadah hafalan santri.
                    </p>
                </div>

                <!-- K4 -->
                <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm hover:border-slate-300 transition">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold font-mono text-xs text-slate-700 mb-3">
                        K4
                    </div>
                    <h4 class="text-xs font-bold text-slate-900 mb-1">Wawancara & Kepribadian</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Asesmen kemandirian tinggal di asrama pesantren, kepribadian santri, motivasi mondok, serta komitmen wali santri.
                    </p>
                </div>
            </div>
        </div>

        <!-- Alur Inferensi FAM -->
        <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Tahapan Logika Fuzzy Associative Memory (FAM)</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                <div class="p-4 rounded-lg bg-slate-50/70 border border-slate-200">
                    <p class="font-bold text-slate-900 mb-1">1. Fuzzifikasi</p>
                    <p class="text-slate-600 text-[11px]">Konversi nilai riil kriteria (0–100) menjadi derajat keanggotaan fuzzy Rendah, Sedang, dan Tinggi.</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50/70 border border-slate-200">
                    <p class="font-bold text-slate-900 mb-1">2. Matriks Asosiasi (FAM)</p>
                    <p class="text-slate-600 text-[11px]">Pemetaan relasi asosiatif multidimensi antara premis kriteria input dengan konsekuensi output.</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50/70 border border-slate-200">
                    <p class="font-bold text-slate-900 mb-1">3. Agregasi Implikasi</p>
                    <p class="text-slate-600 text-[11px]">Penggabungan firing strength aturan dengan operator min-max untuk membentuk kurva agregasi fuzzy.</p>
                </div>
                <div class="p-4 rounded-lg bg-slate-50/70 border border-slate-200">
                    <p class="font-bold text-slate-900 mb-1">4. Defuzzifikasi Centroid</p>
                    <p class="text-slate-600 text-[11px]">Perhitungan titik pusat gravitas (Center of Area) untuk menghasilkan skor kelulusan definitif (0.00 — 1.00).</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200/80 bg-white py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <div>
                &copy; {{ date('Y') }} {{ setting('institution_name', 'Pesantren Ell Firdaus') }}. Sistem Pendukung Keputusan Penerimaan Santri Baru.
            </div>
            <div class="text-[11px] font-mono text-slate-400">
                Fuzzy Associative Memory (Kosko) Engine &bull; Laravel v{{ Illuminate\Foundation\Application::VERSION }}
            </div>
        </div>
    </footer>
</body>
</html>
