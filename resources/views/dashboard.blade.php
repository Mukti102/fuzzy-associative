<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                    Dashboard Operasional SPK FAM
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Sistem Pendukung Keputusan Penerimaan Santri Baru MA Pesantren Ell Firdaus Labusel
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if ($activePeriod)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                        Periode Aktif: {{ $activePeriod->name }} ({{ $activePeriod->academic_year }})
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                        Belum Ada Periode Aktif
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- METRIK DATABASE UTAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3.5 mb-6">
        <!-- 1. Total Calon Santri -->
        <x-stat-card 
            title="Pendaftar" 
            :value="$totalCandidates" 
            subtitle="Tahun Ajaran Aktif"
            color="slate">
            <x-slot name="icon">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- 2. Dinilai Lengkap -->
        <x-stat-card 
            title="Nilai Lengkap" 
            :value="$completeScoresCount" 
            subtitle="Siap Dihitung FAM"
            color="emerald">
            <x-slot name="icon">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- 3. Belum Lengkap -->
        <x-stat-card 
            title="Belum Lengkap" 
            :value="$incompleteScoresCount" 
            subtitle="Perlu Input Nilai"
            color="amber">
            <x-slot name="icon">
                <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- 4. Santri Diterima -->
        <x-stat-card 
            title="Diterima" 
            :value="$passedCandidates" 
            subtitle="Lulus Passing Grade"
            color="emerald">
            <x-slot name="icon">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 13l4 4L19 7"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- 5. Tidak Diterima -->
        <x-stat-card 
            title="Tidak Diterima" 
            :value="$failedCandidates" 
            subtitle="Di Bawah Ambang"
            color="rose">
            <x-slot name="icon">
                <svg class="w-4 h-4 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- 6. Total Periode Seleksi -->
        <x-stat-card 
            title="Periode" 
            :value="$totalPeriods" 
            subtitle="Total Gelombang"
            color="slate">
            <x-slot name="icon">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </x-slot>
        </x-stat-card>
    </div>

    <!-- EMPTY STATE ATAU RINGKASAN DATA -->
    @if (!$activePeriod)
        <x-empty-state 
            title="Belum Ada Periode Seleksi Aktif"
            description="Sistem membutuhkan minimal satu periode seleksi berstatus aktif untuk memulai pendaftaran calon santri dan proses penentuan keputusan metode FAM."
            actionText="Kelola Periode Seleksi"
            :actionUrl="route('periods.index')"
        />
    @elseif ($totalCandidates === 0)
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm mb-6">
            <div class="max-w-xl">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 mb-2 border border-slate-200">
                    Periode Aktif: {{ $activePeriod->name }}
                </span>
                <h3 class="text-sm font-semibold text-slate-900">Belum Ada Calon Santri Terdaftar</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Periode seleksi telah aktif, namun data calon santri jenjang Madrasah Aliyah belum diinput ke database. Buka modul Data Calon Santri untuk menambahkan calon santri baru.
                </p>
                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('candidates.index') }}" class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-medium shadow-sm transition">
                        Buka Data Calon Santri
                    </a>
                    <a href="{{ route('periods.index') }}" class="px-3.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-medium transition">
                        Detail Periode
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- DATA PREVIEW CALON SANTRI TERBARU -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Calon Santri Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pendaftaran terkini pada periode seleksi aktif</p>
                </div>
                <a href="{{ route('candidates.index') }}" class="text-xs font-medium text-emerald-700 hover:text-emerald-800 transition">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                        <tr>
                            <th class="py-2.5 px-4">No. Pendaftaran</th>
                            <th class="py-2.5 px-4">Nama Lengkap</th>
                            <th class="py-2.5 px-4">Jenis Kelamin</th>
                            <th class="py-2.5 px-4">Asal Sekolah</th>
                            <th class="py-2.5 px-4">Status Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentCandidates as $candidate)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-mono font-medium text-slate-900">{{ $candidate->registration_number }}</td>
                                <td class="py-3 px-4 font-medium text-slate-800">{{ $candidate->name }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $candidate->gender_label }}</td>
                                <td class="py-3 px-4 text-slate-500">{{ $candidate->school_origin ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    @if ($candidate->score && $candidate->score->is_complete)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Lengkap (4 Kriteria)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            Belum Lengkap
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- INFORMASI KRITERIA SELEKSI FAM -->
    <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                        Model FAM (Kosko)
                    </span>
                    <h3 class="text-sm font-semibold text-slate-900">4 Kriteria Seleksi Penerimaan Santri Baru MA</h3>
                </div>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Evaluasi calon santri dilakukan secara kuantitatif melalui asosiasi logika fuzzy FAM yang memetakan kriteria input ke keputusan kelulusan secara objektif dan transparan.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="px-2.5 py-1 rounded-md bg-slate-50 border border-slate-200 text-slate-700 font-medium">1. Akademik</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-50 border border-slate-200 text-slate-700 font-medium">2. Tes BTQ</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-50 border border-slate-200 text-slate-700 font-medium">3. Tahfidz</span>
                <span class="px-2.5 py-1 rounded-md bg-slate-50 border border-slate-200 text-slate-700 font-medium">4. Wawancara</span>
            </div>
        </div>
    </div>
</x-app-layout>
