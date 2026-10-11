<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">{{ $candidate->name }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    No. Registrasi: <span class="font-mono font-semibold text-slate-800">{{ $candidate->registration_number }}</span> &bull; Periode: {{ $candidate->period->name ?? '-' }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('scores.edit', $candidate) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Input / Edit Nilai
                    </a>
                    <a href="{{ route('candidates.edit', $candidate) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium shadow-sm transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Ubah Biodata
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Data Calon Santri', 'url' => route('candidates.index')],
        ['label' => $candidate->name, 'url' => null],
    ]" />

    <!-- Status Nilai & Outdated Warning Banner -->
    @if ($candidate->score && $candidate->score->needs_recalculation)
        <div class="mb-5 p-3.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2.5">
            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <p class="font-semibold">Nilai Santri Diperbarui &mdash; Perlu Kalkulasi Ulang</p>
                <p class="text-[11px] mt-0.5 text-amber-700">
                    Nilai calon santri telah diubah pasca kalkulasi sebelumnya. Hasil seleksi lama ditandai sebagai riwayat (*outdated*) dan akan diperbarui saat kalkulasi sesi baru dijalankan.
                </p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
        <!-- Kolom Kiri: Biodata Pribadi -->
        <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm">
            <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                <div class="w-11 h-11 rounded-lg bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-base shadow-sm">
                    {{ strtoupper(substr($candidate->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <h3 class="text-sm font-semibold text-slate-900 leading-tight truncate">{{ $candidate->name }}</h3>
                    <p class="text-xs font-mono text-slate-500 mt-0.5">{{ $candidate->registration_number }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium mt-1 bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $candidate->gender_label }}
                    </span>
                </div>
            </div>

            <div class="pt-4 space-y-3 text-xs">
                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[10px] tracking-wider block">Periode Seleksi</span>
                    <span class="text-slate-800 font-medium">{{ $candidate->period->name ?? '-' }} ({{ $candidate->period->academic_year ?? '-' }})</span>
                </div>
                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[10px] tracking-wider block">Asal Sekolah</span>
                    <span class="text-slate-800 font-medium">{{ $candidate->school_origin ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[10px] tracking-wider block">Tempat & Tanggal Lahir</span>
                    <span class="text-slate-800 font-medium">
                        {{ $candidate->birth_place ?? '-' }}, {{ $candidate->birth_date ? $candidate->birth_date->format('d F Y') : '-' }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[10px] tracking-wider block">No. Telepon / WhatsApp</span>
                    <span class="text-slate-800 font-medium font-mono">{{ $candidate->phone ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 uppercase font-semibold text-[10px] tracking-wider block">Alamat Tinggal</span>
                    <p class="text-slate-800 font-medium mt-0.5 leading-relaxed">{{ $candidate->address ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: 4 Nilai Kriteria & Hasil FAM -->
        <div class="lg:col-span-2 space-y-5">
            <!-- Kartu 4 Nilai Kriteria Asli -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Nilai 4 Kriteria Seleksi MA</h3>
                        <p class="text-xs text-slate-500">Nilai mentah asli calon santri (Skala numerik 0 - 100)</p>
                    </div>
                    <div>
                        @if ($candidate->score && $candidate->score->is_complete)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Lengkap
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                Belum Lengkap
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 my-4">
                    <!-- Nilai Akademik -->
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 block mb-0.5">1. Akademik</span>
                        <span class="text-xl font-bold text-slate-900 font-mono">
                            {{ $candidate->score?->academic_score !== null ? number_format($candidate->score->academic_score, 1) : '-' }}
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Nilai Rapor</span>
                    </div>

                    <!-- Nilai BTQ -->
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 block mb-0.5">2. Tes BTQ</span>
                        <span class="text-xl font-bold text-slate-900 font-mono">
                            {{ $candidate->score?->btq_score !== null ? number_format($candidate->score->btq_score, 1) : '-' }}
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Baca Tulis Qur'an</span>
                    </div>

                    <!-- Nilai Tahfidz -->
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 block mb-0.5">3. Tahfidz</span>
                        <span class="text-xl font-bold text-slate-900 font-mono">
                            {{ $candidate->score?->tahfidz_score !== null ? number_format($candidate->score->tahfidz_score, 1) : '-' }}
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Hafalan Qur'an</span>
                    </div>

                    <!-- Nilai Wawancara -->
                    <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 block mb-0.5">4. Wawancara</span>
                        <span class="text-xl font-bold text-slate-900 font-mono">
                            {{ $candidate->score?->interview_score !== null ? number_format($candidate->score->interview_score, 1) : '-' }}
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Kepribadian</span>
                    </div>
                </div>

                @if ($candidate->score?->notes)
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-600">
                        <span class="font-semibold text-slate-800 block mb-0.5">Catatan Penilai:</span>
                        {{ $candidate->score->notes }}
                    </div>
                @endif
            </div>

            <!-- Kartu Status Hasil Komputasi FAM -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Hasil Keputusan Metode FAM</h3>
                        <p class="text-xs text-slate-500">Nilai akhir defuzzifikasi dan status kelulusan seleksi</p>
                    </div>
                    @if ($latestCalculationDetail)
                        <span class="text-xs text-slate-500 font-mono">
                            {{ $latestCalculationDetail->calculationRun->run_at ? $latestCalculationDetail->calculationRun->run_at->format('d/m/Y H:i') : '-' }}
                        </span>
                    @endif
                </div>

                @if ($latestCalculationDetail)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 my-4">
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 block mb-0.5">Nilai Akhir (z*)</span>
                            <span class="text-xl font-bold text-slate-900 font-mono">
                                {{ number_format($latestCalculationDetail->final_score, 4) }}
                            </span>
                        </div>

                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 block mb-0.5">Peringkat (Rank)</span>
                            <span class="text-xl font-bold text-slate-900 font-mono">
                                #{{ $latestCalculationDetail->rank ?? '-' }}
                            </span>
                        </div>

                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 block mb-0.5">Keputusan</span>
                            @if ($latestCalculationDetail->decision === 'DITERIMA')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 mt-1">
                                    Diterima
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200 mt-1">
                                    Tidak Diterima
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-2 pt-3.5 border-t border-slate-100">
                        <a href="{{ route('selection-results.trace', $latestCalculationDetail) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-medium transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            Lihat Jejak Matematis FAM
                        </a>
                        <a href="{{ route('selection-results.show', $latestCalculationDetail->calculationRun) }}" class="text-xs font-medium text-emerald-700 hover:text-emerald-800 transition">
                            Hasil Lengkap Sesi #{{ str_pad($latestCalculationDetail->calculationRun->id, 4, '0', STR_PAD_LEFT) }} &rarr;
                        </a>
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-slate-500 my-1">
                        Calon santri ini belum memiliki riwayat perhitungan FAM. Pastikan keempat nilai terisi lengkap dan jalankan kalkulasi FAM pada periode seleksi.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
