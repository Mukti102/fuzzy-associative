<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-0.5">
                    <a href="{{ route('selection-results.index') }}" class="hover:text-emerald-700 transition flex items-center gap-1">
                        &larr; Riwayat Kalkulasi
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="font-mono text-slate-700">#RUN-{{ str_pad($run->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                    Hasil Seleksi: {{ $run->period->name ?? 'Periode' }} ({{ $run->period->academic_year ?? '-' }})
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Dieksekusi pada {{ $run->run_at ? $run->run_at->format('d F Y - H:i:s') : '-' }} &bull; Operator: <span class="font-medium text-slate-700">{{ $run->user->name ?? 'Sistem' }}</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.show', $run) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Laporan Resmi
                </a>
            </div>
        </div>
    </x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Hasil Seleksi FAM', 'url' => route('selection-results.index')],
        ['label' => 'Sesi #' . str_pad($run->id, 4, '0', STR_PAD_LEFT), 'url' => null]
    ]" />

    <!-- Stat Ringkasan Hasil Seleksi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5">
        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Total Diproses</p>
                <p class="text-xl font-bold text-slate-900 font-mono">{{ $run->total_candidates }} Santri</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Diterima</p>
                <p class="text-xl font-bold text-emerald-700 font-mono">
                    {{ $run->passed_candidates }} 
                    <span class="text-xs font-normal text-slate-400">
                        ({{ $run->total_candidates > 0 ? round(($run->passed_candidates / $run->total_candidates) * 100, 1) : 0 }}%)
                    </span>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-700 border border-rose-100 flex items-center justify-center font-bold shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Tidak Diterima</p>
                <p class="text-xl font-bold text-rose-700 font-mono">
                    {{ $run->failed_candidates }}
                    <span class="text-xs font-normal text-slate-400">
                        ({{ $run->total_candidates > 0 ? round(($run->failed_candidates / $run->total_candidates) * 100, 1) : 0 }}%)
                    </span>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Passing Grade</p>
                <p class="text-xl font-bold text-slate-900 font-mono">
                    {{ $run->config_snapshot['passing_grade'] ?? $run->period->passing_grade ?? 70.0 }}
                </p>
            </div>
        </div>
    </div>

    <!-- Parameter Konfigurasi FAM Snapshot -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 mb-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between text-xs gap-2.5">
            <div>
                <span class="font-semibold text-slate-800">Metode Komputasi:</span>
                <span class="text-slate-500 ml-1">Parameter yang digunakan pada sesi kalkulasi ini:</span>
            </div>
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-mono text-[11px] border border-slate-200">
                    Encoding: {{ $run->encoding_method }}
                </span>
                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-mono text-[11px] border border-slate-200">
                    Komposisi: {{ $run->composition_method }}
                </span>
                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-mono text-[11px] border border-slate-200">
                    Defuzzifikasi: {{ $run->defuzzification_method }}
                </span>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Hasil -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 shadow-sm mb-5">
        <form method="GET" action="{{ route('selection-results.show', $run) }}" class="flex flex-col sm:flex-row items-center gap-2.5">
            <!-- Filter Keputusan -->
            <div class="w-full sm:w-56">
                <select name="decision" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    <option value="">-- Semua Keputusan --</option>
                    <option value="DITERIMA" {{ $decisionFilter === 'DITERIMA' ? 'selected' : '' }}>DITERIMA Saja</option>
                    <option value="TIDAK DITERIMA" {{ $decisionFilter === 'TIDAK DITERIMA' ? 'selected' : '' }}>TIDAK DITERIMA Saja</option>
                </select>
            </div>

            <!-- Filter Jenis Kelamin -->
            <div class="w-full sm:w-56">
                <select name="gender" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    <option value="">-- Semua Jenis Kelamin --</option>
                    <option value="L" {{ $genderFilter === 'L' ? 'selected' : '' }}>Laki-laki (Santriwan)</option>
                    <option value="P" {{ $genderFilter === 'P' ? 'selected' : '' }}>Perempuan (Santriwati)</option>
                </select>
            </div>

            @if ($decisionFilter || $genderFilter)
                <a href="{{ route('selection-results.show', $run) }}" class="w-full sm:w-auto px-3.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-lg transition text-center">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Peringkat Seleksi FAM -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-semibold text-slate-900">Tabel Pemeringkatan & Rekomendasi Kelulusan</h3>
                <span class="text-[11px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-normal border border-slate-200">
                    Tie-breaker: Tahfidz &rarr; BTQ &rarr; Akademik &rarr; Wawancara
                </span>
            </div>
            <span class="text-xs text-slate-500 font-normal">Halaman {{ $details->currentPage() }} dari {{ $details->lastPage() }}</span>
        </div>

        @if ($details->isEmpty())
            <x-empty-state 
                title="Tidak Ada Data yang Sesuai"
                description="Tidak ditemukan calon santri untuk kriteria filter yang Anda pilih."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                        <tr>
                            <th class="py-3 px-3 text-center">Rank</th>
                            <th class="py-3 px-4">Calon Santri</th>
                            <th class="py-3 px-3 text-center">JK</th>
                            <th class="py-3 px-3 text-center">Akademik</th>
                            <th class="py-3 px-3 text-center">BTQ</th>
                            <th class="py-3 px-3 text-center">Tahfidz</th>
                            <th class="py-3 px-3 text-center">Wawancara</th>
                            <th class="py-3 px-4 text-center">Skor FAM (z*)</th>
                            <th class="py-3 px-4 text-center">Keputusan</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Audit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($details as $detail)
                            <tr class="hover:bg-slate-50/60 transition {{ $detail->is_outdated ? 'bg-amber-50/20' : '' }}">
                                <!-- Ranking Badge -->
                                <td class="py-3 px-3 text-center font-mono font-bold text-slate-800">
                                    {{ $detail->rank }}
                                </td>

                                <!-- Calon Santri -->
                                <td class="py-3 px-4">
                                    <a href="{{ route('candidates.show', $detail->candidate) }}" class="font-medium text-slate-900 hover:text-emerald-700 transition">
                                        {{ $detail->candidate->name }}
                                    </a>
                                    <span class="block text-[11px] font-mono text-slate-500">
                                        {{ $detail->candidate->registration_number }}
                                    </span>
                                </td>

                                <!-- Jenis Kelamin -->
                                <td class="py-3 px-3 text-center font-medium text-slate-600">
                                    {{ $detail->candidate->gender === 'L' ? 'L' : 'P' }}
                                </td>

                                <!-- Nilai Asli 4 Kriteria -->
                                <td class="py-3 px-3 text-center font-mono text-slate-700">
                                    {{ number_format($detail->candidate->score->academic_score ?? 0, 1) }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono text-slate-700">
                                    {{ number_format($detail->candidate->score->btq_score ?? 0, 1) }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-medium text-slate-900">
                                    {{ number_format($detail->candidate->score->tahfidz_score ?? 0, 1) }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono text-slate-700">
                                    {{ number_format($detail->candidate->score->interview_score ?? 0, 1) }}
                                </td>

                                <!-- Skor Akhir FAM -->
                                <td class="py-3 px-4 text-center font-mono font-bold text-sm {{ $detail->decision === 'DITERIMA' ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ number_format($detail->final_score, 2) }}
                                </td>

                                <!-- Status Keputusan -->
                                <td class="py-3 px-4 text-center">
                                    @if ($detail->decision === 'DITERIMA')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Diterima
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-rose-50 text-rose-800 border border-rose-200">
                                            Tidak Diterima
                                        </span>
                                    @endif
                                </td>

                                <!-- Validitas Audit Trail -->
                                <td class="py-3 px-3 text-center">
                                    @if ($detail->is_outdated)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-200" title="Nilai santri telah diubah setelah kalkulasi ini dijalankan.">
                                            Outdated
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            Valid
                                        </span>
                                    @endif
                                </td>

                                <!-- Link Trace Perhitungan -->
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('selection-results.trace', $detail) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition"
                                       title="Lihat Perhitungan Detail">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        Trace FAM
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3.5 border-t border-slate-100">
                {{ $details->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
