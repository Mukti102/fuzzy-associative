<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-0.5">
                    <a href="{{ route('selection-results.show', $detail->calculationRun) }}" class="hover:text-emerald-700 transition flex items-center gap-1">
                        &larr; Hasil Sesi #{{ str_pad($detail->calculationRun->id, 4, '0', STR_PAD_LEFT) }}
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="font-mono text-slate-700">Audit Trace FAM</span>
                </div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                    Jejak Komputasi FAM: {{ $detail->candidate->name }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    No. Registrasi: <span class="font-mono font-semibold text-slate-800">{{ $detail->candidate->registration_number }}</span> &bull; Periode: {{ $detail->calculationRun->period->name ?? '-' }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-md font-mono text-xs font-semibold {{ $detail->decision === 'DITERIMA' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                    Skor: {{ number_format($detail->final_score, 4) }} &bull; {{ $detail->decision }} (Rank #{{ $detail->rank }})
                </span>
            </div>
        </div>
    </x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Hasil Seleksi FAM', 'url' => route('selection-results.index')],
        ['label' => 'Sesi #' . str_pad($detail->calculationRun->id, 4, '0', STR_PAD_LEFT), 'url' => route('selection-results.show', $detail->calculationRun)],
        ['label' => 'Trace: ' . $detail->candidate->name, 'url' => null]
    ]" />

    @if ($detail->is_outdated)
        <div class="p-3.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs mb-5 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <p class="font-semibold">Catatan Riwayat (Audit Trail):</p>
                <p class="mt-0.5 text-amber-700">Nilai calon santri ini telah diubah setelah kalkulasi ini dijalankan. Halaman ini merekam jejak matematis asli saat sesi diproses.</p>
            </div>
        </div>
    @endif

    <!-- Ringkasan Profil & Hasil Singkat -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 sm:p-5 shadow-sm mb-5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <p class="text-slate-400 uppercase font-semibold text-[10px]">Nama Lengkap</p>
                <p class="font-semibold text-slate-900 mt-0.5">{{ $detail->candidate->name }}</p>
                <p class="text-slate-500 text-[11px]">{{ $detail->candidate->gender === 'L' ? 'Laki-laki (Santriwan)' : 'Perempuan (Santriwati)' }}</p>
            </div>
            <div>
                <p class="text-slate-400 uppercase font-semibold text-[10px]">Asal Sekolah</p>
                <p class="font-semibold text-slate-900 mt-0.5">{{ $detail->candidate->previous_school ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 uppercase font-semibold text-[10px]">Passing Grade</p>
                <p class="font-mono font-bold text-slate-900 mt-0.5">
                    {{ $detail->calculationRun->config_snapshot['passing_grade'] ?? $detail->calculationRun->period->passing_grade ?? 70.0 }}
                </p>
            </div>
            <div>
                <p class="text-slate-400 uppercase font-semibold text-[10px]">Keputusan Akhir</p>
                <p class="font-semibold mt-0.5 {{ $detail->decision === 'DITERIMA' ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ $detail->decision }} (Peringkat #{{ $detail->rank }})
                </p>
            </div>
        </div>
    </div>

    <!-- TAHAP 1: Fuzzifikasi -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-5">
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs font-mono">1</span>
                <h3 class="text-sm font-semibold text-slate-900">Tahap 1: Fuzzifikasi Nilai 4 Kriteria</h3>
            </div>
            <p class="text-xs text-slate-500 mt-0.5 ml-7">
                Mengonversi nilai tegas ($x \in [0, 100]$) menjadi derajat keanggotaan fuzzy $\mu(x) \in [0, 1]$ berdasarkan parameter fungsi keanggotaan.
            </p>
        </div>

        <div class="p-4 sm:p-5 overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                    <tr>
                        <th class="py-2.5 px-4">Kriteria Penilaian</th>
                        <th class="py-2.5 px-4 text-center">Nilai Asli (x)</th>
                        <th class="py-2.5 px-4">Derajat Keanggotaan Fuzzy &mu;(x)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $fuzz = $detail->fuzzification_data ?? [];
                    @endphp
                    @foreach ($fuzz as $code => $data)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                {{ $code }}
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-slate-900 text-sm">
                                {{ number_format($data['raw_score'] ?? 0, 1) }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                    @foreach ($data['degrees'] ?? [] as $setName => $deg)
                                        <div class="p-2 rounded-lg border {{ $deg > 0 ? 'border-emerald-200 bg-emerald-50/40' : 'border-slate-200 bg-slate-50/40' }}">
                                            <div class="flex justify-between items-center text-[11px] mb-1">
                                                <span class="font-medium text-slate-700">{{ $setName }}</span>
                                                <span class="font-mono font-semibold {{ $deg > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                                                    &mu; = {{ number_format($deg, 4) }}
                                                </span>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-1 overflow-hidden">
                                                <div class="bg-emerald-600 h-1 rounded-full" style="width: {{ $deg * 100 }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAHAP 2: Evaluasi Aturan & Matriks FAM -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-5">
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs font-mono">2</span>
                <h3 class="text-sm font-semibold text-slate-900">Tahap 2: Evaluasi Aturan & Matriks FAM (Kosko Encoding)</h3>
            </div>
            <p class="text-xs text-slate-500 mt-0.5 ml-7">
                Menghitung kekuatan penembakan (*Firing Strength*) dengan T-norm Minimum: 
                <span class="font-mono text-slate-700 font-semibold">&alpha;<sub>k</sub> = min(&mu;<sub>Akad</sub>, &mu;<sub>BTQ</sub>, &mu;<sub>Tahf</sub>, &mu;<sub>Wwc</sub>)</span>.
            </p>
        </div>

        <div class="p-4 sm:p-5 overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                    <tr>
                        <th class="py-2.5 px-3">Kode</th>
                        <th class="py-2.5 px-3">Premis Aturan</th>
                        <th class="py-2.5 px-3 text-center">Firing Strength (&alpha;<sub>k</sub>)</th>
                        <th class="py-2.5 px-3 text-center">Bobot (w)</th>
                        <th class="py-2.5 px-3">Konsekuen Output</th>
                        <th class="py-2.5 px-3 text-center">Pusat (y&#772;<sub>k</sub>)</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $rules = $detail->activated_rules_data ?? [];
                    @endphp
                    @foreach ($rules as $r)
                        <tr class="hover:bg-slate-50/60 transition {{ $r['is_active'] ? 'bg-emerald-50/20' : 'opacity-60' }}">
                            <td class="py-2.5 px-3 font-mono font-semibold text-slate-900">
                                {{ $r['code'] }}
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="space-y-0.5 text-[11px] text-slate-600">
                                    <span>Akad: <strong>{{ $r['premise']['akademik']['set'] }}</strong> (&mu;={{ number_format($r['premise']['akademik']['degree'], 2) }})</span> &bull;
                                    <span>BTQ: <strong>{{ $r['premise']['btq']['set'] }}</strong> (&mu;={{ number_format($r['premise']['btq']['degree'], 2) }})</span> &bull;
                                    <span>Tahfidz: <strong>{{ $r['premise']['tahfidz']['set'] }}</strong> (&mu;={{ number_format($r['premise']['tahfidz']['degree'], 2) }})</span> &bull;
                                    <span>Wwc: <strong>{{ $r['premise']['wawancara']['set'] }}</strong> (&mu;={{ number_format($r['premise']['wawancara']['degree'], 2) }})</span>
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold {{ $r['firing_strength'] > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                                {{ number_format($r['firing_strength'], 4) }}
                            </td>
                            <td class="py-2.5 px-3 text-center font-mono text-slate-600">
                                {{ number_format($r['weight'], 2) }}
                            </td>
                            <td class="py-2.5 px-3 font-medium">
                                <span class="px-2 py-0.5 rounded text-[10px] {{ $r['output_set'] === 'DITERIMA' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                                    {{ $r['output_set'] }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-center font-mono text-slate-700">
                                {{ number_format($r['output_center'], 1) }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if ($r['is_active'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAHAP 3: Agregasi & Komposisi -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-5">
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs font-mono">3</span>
                <h3 class="text-sm font-semibold text-slate-900">Tahap 3: Agregasi Komposisi Himpunan Fuzzy</h3>
            </div>
            <p class="text-xs text-slate-500 mt-0.5 ml-7">
                Agregasi T-conorm Max menggabungkan seluruh aturan aktif untuk setiap himpunan output (Metode: {{ $detail->calculationRun->composition_method }}).
            </p>
        </div>

        <div class="p-4 sm:p-5">
            @php
                $comp = $detail->composition_data ?? [];
                $strengths = $comp['output_set_strengths'] ?? [];
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                @foreach ($strengths as $outSet => $str)
                    <div class="p-3.5 rounded-lg border {{ $outSet === 'DITERIMA' ? 'border-emerald-200 bg-emerald-50/30' : 'border-rose-200 bg-rose-50/30' }}">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="font-semibold text-xs {{ $outSet === 'DITERIMA' ? 'text-emerald-800' : 'text-rose-800' }}">
                                Himpunan: {{ $outSet }}
                            </span>
                            <span class="font-mono font-bold text-xs {{ $outSet === 'DITERIMA' ? 'text-emerald-700' : 'text-rose-700' }}">
                                &mu;<sub>max</sub> = {{ number_format($str, 4) }}
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                            <div class="{{ $outSet === 'DITERIMA' ? 'bg-emerald-600' : 'bg-rose-600' }} h-1.5 rounded-full" style="width: {{ $str * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="text-xs text-slate-500 mt-2.5">
                Total aturan yang aktif menembak: <strong class="text-slate-800">{{ $comp['total_active_rules'] ?? 0 }} Aturan</strong>.
            </p>
        </div>
    </div>

    <!-- TAHAP 4: Defuzzifikasi -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded bg-slate-900 text-white flex items-center justify-center font-bold text-xs font-mono">4</span>
                <h3 class="text-sm font-semibold text-slate-900">Tahap 4: Defuzzifikasi & Penentuan Kelulusan</h3>
            </div>
            <p class="text-xs text-slate-500 mt-0.5 ml-7">
                Konversi himpunan output fuzzy menjadi nilai tegas (crisp value $z^*$) menggunakan metode <strong>{{ $detail->calculationRun->defuzzification_method }}</strong>.
            </p>
        </div>

        <div class="p-4 sm:p-5 space-y-4">
            @php
                $defuzz = $detail->defuzzification_data ?? [];
            @endphp

            @if (($defuzz['method'] ?? '') === 'weighted-average')
                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                    <p class="text-xs font-medium text-slate-700 mb-1.5">Rumus Weighted Average:</p>
                    <div class="p-2.5 bg-white rounded border border-slate-200 font-mono text-xs text-center text-slate-800">
                        z* = &sum;(&alpha;<sub>k</sub> &times; w<sub>k</sub> &times; y&#772;<sub>k</sub>) &divide; &sum;(&alpha;<sub>k</sub> &times; w<sub>k</sub>)
                    </div>
                </div>

                @if (!empty($defuzz['steps']))
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                                <tr>
                                    <th class="py-2.5 px-3">Aturan Aktif</th>
                                    <th class="py-2.5 px-3 text-center">Firing Strength (&alpha;<sub>k</sub>)</th>
                                    <th class="py-2.5 px-3 text-center">Bobot (w<sub>k</sub>)</th>
                                    <th class="py-2.5 px-3 text-center">Pusat Output (y&#772;<sub>k</sub>)</th>
                                    <th class="py-2.5 px-3 text-right">Hasil Kali (&alpha;<sub>k</sub> &times; w<sub>k</sub> &times; y&#772;<sub>k</sub>)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($defuzz['steps'] as $step)
                                    <tr>
                                        <td class="py-2 px-3 font-mono font-medium text-slate-900">{{ $step['rule_code'] }}</td>
                                        <td class="py-2 px-3 text-center font-mono">{{ number_format($step['firing_strength'], 4) }}</td>
                                        <td class="py-2 px-3 text-center font-mono">{{ number_format($step['weight'], 2) }}</td>
                                        <td class="py-2 px-3 text-center font-mono">{{ number_format($step['output_center'], 1) }}</td>
                                        <td class="py-2 px-3 text-right font-mono font-semibold text-slate-900">
                                            {{ number_format($step['product'], 4) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-xs space-y-1.5">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Total Pembilang (&sum;(&alpha;<sub>k</sub> &times; w<sub>k</sub> &times; y&#772;<sub>k</sub>)):</span>
                        <span class="font-mono font-semibold text-slate-900">{{ number_format($defuzz['numerator'] ?? 0, 4) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Total Penyebut (&sum;(&alpha;<sub>k</sub> &times; w<sub>k</sub>)):</span>
                        <span class="font-mono font-semibold text-slate-900">{{ number_format($defuzz['denominator'] ?? 0, 4) }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-200 flex justify-between items-center text-sm">
                        <span class="font-semibold text-slate-900">Skor Akhir Defuzzifikasi (z*):</span>
                        <span class="font-mono font-bold text-slate-900 text-base">
                            {{ number_format($defuzz['crisp_value'] ?? 0, 4) }}
                        </span>
                    </div>
                </div>
            @endif

            <!-- Keputusan Akhir Berdasarkan Passing Grade -->
            <div class="p-3.5 rounded-lg border {{ $detail->decision === 'DITERIMA' ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-200' }} flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold {{ $detail->decision === 'DITERIMA' ? 'text-emerald-900' : 'text-rose-900' }}">
                        Kesimpulan Evaluasi Kelulusan
                    </p>
                    <p class="text-[11px] {{ $detail->decision === 'DITERIMA' ? 'text-emerald-700' : 'text-rose-700' }} mt-0.5">
                        Skor z* ({{ number_format($detail->final_score, 2) }}) {{ $detail->final_score >= ($detail->calculationRun->config_snapshot['passing_grade'] ?? $detail->calculationRun->period->passing_grade ?? 70.0) ? '≥' : '<' }} Passing Grade ({{ $detail->calculationRun->config_snapshot['passing_grade'] ?? $detail->calculationRun->period->passing_grade ?? 70.0 }})
                    </p>
                </div>
                <span class="px-2.5 py-1 rounded-md font-semibold text-xs {{ $detail->decision === 'DITERIMA' ? 'bg-emerald-700 text-white' : 'bg-rose-700 text-white' }}">
                    {{ $detail->decision }}
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
