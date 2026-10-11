<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Input / Ubah Nilai Seleksi</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $candidate->name }} <span class="font-mono text-slate-600 font-semibold">({{ $candidate->registration_number }})</span> &bull; Periode: {{ $candidate->period->name ?? '-' }}
                </p>
            </div>
            <a href="{{ route('candidates.show', $candidate) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-emerald-700 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Profil Santri
            </a>
        </div>
    </x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Data Calon Santri', 'url' => route('candidates.index')],
        ['label' => $candidate->name, 'url' => route('candidates.show', $candidate)],
        ['label' => 'Input Nilai Seleksi', 'url' => null],
    ]" />

    <div class="max-w-4xl mx-auto my-6">
        <!-- Panduan Pengisian Nilai -->
        <div class="mb-5 p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm text-xs text-slate-700">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700 shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="leading-relaxed">
                    <p class="font-semibold text-slate-900">Ketentuan Standarisasi Nilai Seleksi:</p>
                    <ul class="list-disc list-inside mt-1 space-y-0.5 text-slate-600">
                        <li>Semua kriteria menggunakan skala nilai angka riil <span class="font-bold font-mono text-slate-900">0.00 — 100.00</span>.</li>
                        <li>Nilai mentah disimpan independen di database sebelum dikonversi ke derajat keanggotaan fuzzy.</li>
                        <li>Jika santri ini sudah pernah melalui pemrosesan inferensi FAM, pembaruan nilai akan menandai kalkulasi terdahulu sebagai <span class="font-semibold text-amber-700">outdated</span> untuk penghitungan ulang.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm">
            <form action="{{ route('scores.update', $candidate) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- 4 KARTU INPUT NILAI -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- 1. Nilai Akademik -->
                    <div class="p-4 rounded-lg bg-slate-50/70 border border-slate-200 focus-within:border-emerald-600 focus-within:ring-1 focus-within:ring-emerald-600 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="academic_score" class="text-xs font-bold text-slate-800">
                                1. Nilai Akademik / Rapor
                            </label>
                            <span class="text-[10px] font-mono font-semibold px-2 py-0.5 rounded bg-slate-200 text-slate-700">
                                Skala 0 — 100
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 mb-2.5">Rata-rata nilai rapor mata pelajaran pokok (Matematika, IPA, B. Indonesia, B. Inggris).</p>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               max="100" 
                               id="academic_score" 
                               name="academic_score" 
                               value="{{ old('academic_score', $score->academic_score) }}" 
                               placeholder="Contoh: 85.50"
                               class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 @error('academic_score') border-rose-500 @enderror">
                        @error('academic_score')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 2. Nilai Tes BTQ -->
                    <div class="p-4 rounded-lg bg-slate-50/70 border border-slate-200 focus-within:border-emerald-600 focus-within:ring-1 focus-within:ring-emerald-600 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="btq_score" class="text-xs font-bold text-slate-800">
                                2. Tes Baca Tulis Al-Qur'an (BTQ)
                            </label>
                            <span class="text-[10px] font-mono font-semibold px-2 py-0.5 rounded bg-slate-200 text-slate-700">
                                Skala 0 — 100
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 mb-2.5">Evaluasi kaidah tajwid, makharijul huruf, kelancaran tartil, dan tulisan ayat.</p>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               max="100" 
                               id="btq_score" 
                               name="btq_score" 
                               value="{{ old('btq_score', $score->btq_score) }}" 
                               placeholder="Contoh: 90.00"
                               class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 @error('btq_score') border-rose-500 @enderror">
                        @error('btq_score')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 3. Nilai Tahfidz -->
                    <div class="p-4 rounded-lg bg-slate-50/70 border border-slate-200 focus-within:border-emerald-600 focus-within:ring-1 focus-within:ring-emerald-600 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="tahfidz_score" class="text-xs font-bold text-slate-800">
                                3. Tes Hafalan Al-Qur'an (Tahfidz)
                            </label>
                            <span class="text-[10px] font-mono font-semibold px-2 py-0.5 rounded bg-slate-200 text-slate-700">
                                Skala 0 — 100
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 mb-2.5">Kuantitas hafalan juz/surah, kelancaran sambung ayat, dan ketahanan mutqin.</p>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               max="100" 
                               id="tahfidz_score" 
                               name="tahfidz_score" 
                               value="{{ old('tahfidz_score', $score->tahfidz_score) }}" 
                               placeholder="Contoh: 78.00"
                               class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 @error('tahfidz_score') border-rose-500 @enderror">
                        @error('tahfidz_score')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 4. Nilai Wawancara -->
                    <div class="p-4 rounded-lg bg-slate-50/70 border border-slate-200 focus-within:border-emerald-600 focus-within:ring-1 focus-within:ring-emerald-600 transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="interview_score" class="text-xs font-bold text-slate-800">
                                4. Tes Wawancara & Kepribadian
                            </label>
                            <span class="text-[10px] font-mono font-semibold px-2 py-0.5 rounded bg-slate-200 text-slate-700">
                                Skala 0 — 100
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 mb-2.5">Motivasi mondok santri, kesiapan asrama mandiri, serta komitmen wali santri.</p>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               max="100" 
                               id="interview_score" 
                               name="interview_score" 
                               value="{{ old('interview_score', $score->interview_score) }}" 
                               placeholder="Contoh: 82.50"
                               class="w-full text-xs font-mono font-bold rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 @error('interview_score') border-rose-500 @enderror">
                        @error('interview_score')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Catatan Penilai -->
                <div>
                    <label for="notes" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Catatan Penguji / Penilai (Opsional)
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3" 
                              placeholder="Keterangan khusus mengenai tes, catatan perilaku, atau rekam jejak santri..."
                              class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 @error('notes') border-rose-500 @enderror">{{ old('notes', $score->notes) }}</textarea>
                    @error('notes')
                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('candidates.show', $candidate) }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100 border border-slate-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                        Simpan Nilai Santri
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
