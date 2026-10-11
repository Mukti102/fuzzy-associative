<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Hasil Seleksi Metode FAM</h2>
                <p class="text-xs text-slate-500 mt-0.5">Komputasi inferensi Fuzzy Associative Memory, perankingan, dan keputusan kelulusan</p>
            </div>
            @if (auth()->user()->isAdmin())
                <div x-data="{ openModal: false }">
                    <button type="button" 
                            @click="openModal = true"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Jalankan Kalkulasi FAM
                    </button>

                    <!-- Modal Jalankan Perhitungan FAM -->
                    <div x-show="openModal" 
                         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
                         style="display: none;">
                        <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl border border-slate-200 text-left" 
                             @click.away="openModal = false">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Jalankan Perhitungan Seleksi FAM</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Pilih metode inferensi dan defuzzifikasi untuk kalkulasi batch</p>
                                </div>
                                <button type="button" @click="openModal = false" class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <form action="{{ route('selection-results.calculate') }}" method="POST" class="mt-4 space-y-3.5">
                                @csrf

                                <!-- Periode Seleksi -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Periode Seleksi</label>
                                    <select name="selection_period_id" required class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                        @foreach ($periods as $p)
                                            <option value="{{ $p->id }}" {{ $activePeriod && $activePeriod->id === $p->id ? 'selected' : '' }}>
                                                {{ $p->name }} ({{ $p->academic_year }}) {{ $p->is_active ? '— [AKTIF]' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Metode Encoding FAM -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Encoding Matriks FAM</label>
                                    <select name="encoding_method" required class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                        <option value="correlation-minimum" selected>Correlation-Minimum (Standar Kosko - Min Firing Strength)</option>
                                        <option value="correlation-product">Correlation-Product (Perkalian Derajat Keanggotaan)</option>
                                    </select>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Menghitung matriks korelasi asosiasi premis & konsekuen aturan FAM.</p>
                                </div>

                                <!-- Metode Komposisi / Inferensi -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Agregasi Komposisi</label>
                                    <select name="composition_method" required class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                        <option value="max-min" selected>Max-Min Composition (T-conorm Max)</option>
                                        <option value="max-product">Max-Product Composition</option>
                                    </select>
                                </div>

                                <!-- Metode Defuzzifikasi -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Defuzzifikasi</label>
                                    <select name="defuzzification_method" required class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                                        <option value="weighted-average" selected>Weighted Average Method (Rekomendasi Akademik FAM)</option>
                                        <option value="winner-take-all">Winner-Take-All (Aturan Dominan Firing Tertinggi)</option>
                                        <option value="centroid">Centroid (Center of Gravity)</option>
                                    </select>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Mengonversi himpunan output fuzzy menjadi skor crisp tunggal (0-100).</p>
                                </div>

                                @if ($activePeriod && $totalCandidatesCount > 0 && $readyCandidatesCount < $totalCandidatesCount)
                                    <div class="p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <div>
                                                <span class="font-semibold">Catatan Validasi:</span>
                                                Terdapat {{ $totalCandidatesCount - $readyCandidatesCount }} calon santri yang nilainya belum lengkap. Seluruh 4 kriteria harus terisi sebelum kalkulasi dijalankan.
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                                    <button type="button" @click="openModal = false" class="px-3.5 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-medium transition">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-medium shadow-sm transition">
                                        Eksekusi Kalkulasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-slot>

    <x-breadcrumb :items="[['label' => 'Hasil Seleksi FAM', 'url' => null]]" />

    <!-- Status Kesiapan Periode Aktif -->
    @if ($activePeriod)
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-sm mb-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Periode Aktif
                        </span>
                        <h3 class="text-sm font-semibold text-slate-900">{{ $activePeriod->name }} ({{ $activePeriod->academic_year }})</h3>
                    </div>
                    <p class="text-xs text-slate-500">
                        Passing Grade: <span class="font-mono font-semibold text-slate-700">{{ $activePeriod->passing_grade ?? 70.0 }}</span> &bull; Kuota: <span class="font-semibold text-slate-700">{{ $activePeriod->quota ?? 'Tidak Dibatasi' }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-4 bg-slate-50 px-4 py-2.5 rounded-lg border border-slate-200">
                    <div>
                        <p class="text-[11px] text-slate-500 uppercase tracking-wider font-medium">Kesiapan Penilaian</p>
                        <p class="text-base font-bold text-slate-900 font-mono">
                            {{ $readyCandidatesCount }} <span class="text-xs font-normal text-slate-500">/ {{ $totalCandidatesCount }} Santri</span>
                        </p>
                    </div>
                    @if ($totalCandidatesCount > 0 && $readyCandidatesCount === $totalCandidatesCount)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Siap Hitung
                        </span>
                    @elseif ($totalCandidatesCount === 0)
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                            Belum Ada Santri
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                            Belum Lengkap
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Filter Periode -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 shadow-sm mb-5">
        <form method="GET" action="{{ route('selection-results.index') }}" class="flex flex-col sm:flex-row items-center gap-2.5">
            <div class="w-full sm:w-72">
                <select name="period_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    <option value="">-- Semua Periode Seleksi --</option>
                    @foreach ($periods as $p)
                        <option value="{{ $p->id }}" {{ $periodId == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->academic_year }})
                        </option>
                    @endforeach
                </select>
            </div>
            @if ($periodId)
                <a href="{{ route('selection-results.index') }}" class="w-full sm:w-auto px-3.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-lg transition text-center">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Riwayat Sesi Perhitungan -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Riwayat Sesi Perhitungan Seleksi FAM</h3>
            <span class="text-xs text-slate-500 font-normal">Total: {{ $runs->total() }} Sesi</span>
        </div>

        @if ($runs->isEmpty())
            <x-empty-state 
                title="Belum Ada Riwayat Perhitungan FAM"
                description="Belum ada proses perhitungan seleksi yang dieksekusi. Pastikan nilai 4 kriteria calon santri telah diinput lengkap lalu klik tombol 'Jalankan Kalkulasi FAM'."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                        <tr>
                            <th class="py-3 px-4">Waktu Eksekusi</th>
                            <th class="py-3 px-4">Periode</th>
                            <th class="py-3 px-4">Operator</th>
                            <th class="py-3 px-4">Metode FAM</th>
                            <th class="py-3 px-4 text-center">Total</th>
                            <th class="py-3 px-4 text-center">Diterima</th>
                            <th class="py-3 px-4 text-center">Tidak Diterima</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($runs as $run)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-mono text-slate-800">
                                    {{ $run->run_at ? $run->run_at->format('d/m/Y H:i') : '-' }}
                                    <span class="block text-[10px] text-slate-400">#RUN-{{ str_pad($run->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-medium text-slate-900">{{ $run->period->name ?? '-' }}</span>
                                    <span class="block text-[11px] text-slate-500">{{ $run->period->academic_year ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $run->user->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="space-y-0.5 text-[11px]">
                                        <span class="inline-block px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 font-mono text-[10px] border border-slate-200">
                                            {{ $run->encoding_method }}
                                        </span>
                                        <span class="block text-[10px] text-slate-500">
                                            {{ $run->composition_method }} &bull; {{ $run->defuzzification_method }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">
                                    {{ $run->total_candidates }}
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-emerald-700">
                                    {{ $run->passed_candidates }}
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-rose-700">
                                    {{ $run->failed_candidates }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('selection-results.show', $run) }}" 
                                           class="px-2.5 py-1 rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition"
                                           title="Lihat Hasil & Ranking">
                                            Hasil & Ranking
                                        </a>
                                        <a href="{{ route('reports.show', $run) }}" 
                                           class="px-2.5 py-1 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-medium transition"
                                           title="Cetak Laporan">
                                            Laporan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3.5 border-t border-slate-100">
                {{ $runs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
