<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Laporan Hasil Seleksi & Pemeringkatan</h2>
                <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi peringkat penerimaan santri baru MA siap cetak dan arsip akademik</p>
            </div>
            @if ($latestRun)
                <a href="{{ route('reports.show', $latestRun) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Sesi Terbaru (#{{ str_pad($latestRun->id, 4, '0', STR_PAD_LEFT) }})
                </a>
            @endif
        </div>
    </x-slot>

    <x-breadcrumb :items="[['label' => 'Laporan Seleksi', 'url' => null]]" />

    <!-- Sorotan Laporan Sesi Terbaru -->
    @if ($latestRun)
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-sm mb-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Sesi Terkini
                        </span>
                        <span class="text-xs font-mono text-slate-500">#RUN-{{ str_pad($latestRun->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900">{{ $latestRun->period->name ?? 'Periode' }} ({{ $latestRun->period->academic_year ?? '-' }})</h3>
                    <p class="text-xs text-slate-500">
                        Dieksekusi: {{ $latestRun->run_at ? $latestRun->run_at->format('d M Y H:i') : '-' }} &bull; Total: {{ $latestRun->total_candidates }} Santri (<span class="text-emerald-700 font-medium">{{ $latestRun->passed_candidates }} diterima</span>, <span class="text-rose-700 font-medium">{{ $latestRun->failed_candidates }} tidak diterima</span>)
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('selection-results.show', $latestRun) }}" class="px-3.5 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition">
                        Lihat di Sistem
                    </a>
                    <a href="{{ route('reports.show', $latestRun) }}" target="_blank" class="px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Buka Laporan Cetak
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Daftar Periode & Sesi Laporan -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm mb-6">
        <h3 class="text-sm font-semibold text-slate-900 mb-1">Katalog Laporan Berdasarkan Periode Seleksi</h3>
        <p class="text-xs text-slate-500 mb-5">Pilih sesi kalkulasi penerimaan santri yang ingin dicetak sebagai dokumen resmi:</p>

        @if ($periods->isEmpty())
            <x-empty-state 
                title="Belum Ada Periode Seleksi"
                description="Laporan memerlukan minimal satu periode seleksi terdaftar di sistem."
            />
        @else
            <div class="space-y-4">
                @foreach ($periods as $period)
                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        <div class="p-3.5 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-200">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-semibold text-slate-900">{{ $period->name }}</h4>
                                    <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-white text-slate-700 border border-slate-200">
                                        {{ $period->academic_year }}
                                    </span>
                                    @if ($period->is_active)
                                        <span class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Aktif
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">Passing Grade: {{ $period->passing_grade ?? 70.0 }} &bull; Kuota: {{ $period->quota ?? 'Tidak Dibatasi' }}</p>
                            </div>
                            <span class="text-xs text-slate-500">
                                {{ $period->calculationRuns->count() }} Sesi
                            </span>
                        </div>

                        @if ($period->calculationRuns->isEmpty())
                            <div class="p-3.5 text-center text-xs text-slate-400">
                                Belum ada sesi perhitungan FAM untuk periode ini.
                            </div>
                        @else
                            <div class="divide-y divide-slate-100">
                                @foreach ($period->calculationRuns as $run)
                                    <div class="p-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 hover:bg-slate-50/50 transition text-xs">
                                        <div class="space-y-0.5">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono font-medium text-slate-900">#RUN-{{ str_pad($run->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                <span class="text-slate-300">&bull;</span>
                                                <span class="text-slate-600">{{ $run->run_at ? $run->run_at->format('d/m/Y H:i') : '-' }}</span>
                                            </div>
                                            <p class="text-[11px] text-slate-500">
                                                Total {{ $run->total_candidates }} calon (<span class="text-emerald-700 font-medium">{{ $run->passed_candidates }} diterima</span>, <span class="text-rose-700 font-medium">{{ $run->failed_candidates }} tidak diterima</span>) &bull; {{ $run->defuzzification_method }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('selection-results.show', $run) }}" class="px-2.5 py-1 rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition">
                                                Lihat Hasil
                                            </a>
                                            <a href="{{ route('reports.show', $run) }}" target="_blank" class="px-2.5 py-1 rounded-md bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                                Cetak Laporan
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
