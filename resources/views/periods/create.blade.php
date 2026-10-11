<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 tracking-tight">Tambah Periode Seleksi Baru</h2>
        <p class="text-xs text-slate-500 mt-0.5">Buat gelombang pendaftaran seleksi calon santri baru MA Pesantren Ell Firdaus</p>
    </x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Periode Seleksi', 'url' => route('periods.index')],
        ['label' => 'Tambah Periode Baru', 'url' => null],
    ]" />

    <div class="max-w-2xl mx-auto my-5">
        <div class="bg-white rounded-xl border border-slate-200/80 p-5 sm:p-7 shadow-sm">
            <form action="{{ route('periods.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Periode -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nama Periode Seleksi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="Contoh: Seleksi Santri Baru MA Gelombang 1"
                           class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('name') border-rose-500 @enderror">
                    @error('name')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Ajaran -->
                <div>
                    <label for="academic_year" class="block text-xs font-semibold text-slate-700 mb-1">
                        Tahun Ajaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="academic_year" 
                           name="academic_year" 
                           value="{{ old('academic_year', '2026/2027') }}" 
                           required 
                           placeholder="Contoh: 2026/2027"
                           class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('academic_year') border-rose-500 @enderror">
                    <p class="text-[11px] text-slate-400 mt-0.5">Format: YYYY/YYYY (contoh: 2026/2027)</p>
                    @error('academic_year')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rentang Tanggal -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="start_date" class="block text-xs font-semibold text-slate-700 mb-1">
                            Tanggal Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ old('start_date') }}" 
                               required 
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('start_date') border-rose-500 @enderror">
                        @error('start_date')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-xs font-semibold text-slate-700 mb-1">
                            Tanggal Selesai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ old('end_date') }}" 
                               required 
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('end_date') border-rose-500 @enderror">
                        @error('end_date')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Passing Grade & Kuota -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="passing_grade" class="block text-xs font-semibold text-slate-700 mb-1">
                            Batas Kelulusan (Passing Grade)
                        </label>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               max="100" 
                               id="passing_grade" 
                               name="passing_grade" 
                               value="{{ old('passing_grade', '70.00') }}" 
                               placeholder="70.00"
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('passing_grade') border-rose-500 @enderror">
                        <p class="text-[11px] text-slate-400 mt-0.5">Ambang batas nilai akhir FAM (0 - 100)</p>
                        @error('passing_grade')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quota" class="block text-xs font-semibold text-slate-700 mb-1">
                            Kuota Penerimaan Santri
                        </label>
                        <input type="number" 
                               min="1" 
                               id="quota" 
                               name="quota" 
                               value="{{ old('quota') }}" 
                               placeholder="Contoh: 150"
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('quota') border-rose-500 @enderror">
                        <p class="text-[11px] text-slate-400 mt-0.5">Biarkan kosong jika tidak dibatasi</p>
                        @error('quota')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Aktif Checkbox -->
                <div class="flex items-center gap-2.5 p-3 rounded-lg bg-slate-50 border border-slate-200">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-emerald-700 focus:ring-emerald-600 border-slate-300">
                    <div>
                        <label for="is_active" class="text-xs font-medium text-slate-800 cursor-pointer">
                            Tetapkan sebagai Periode Seleksi Aktif
                        </label>
                        <p class="text-[11px] text-slate-500">
                            Hanya ada satu periode yang aktif dalam satu waktu.
                        </p>
                    </div>
                </div>

                <!-- Deskripsi / Keterangan -->
                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">
                        Keterangan Tambahan
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3" 
                              placeholder="Catatan mengenai gelombang pendaftaran, persyaratan, atau informasi relevan..."
                              class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('description') border-rose-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-2 pt-3.5 border-t border-slate-100">
                    <a href="{{ route('periods.index') }}" class="px-3.5 py-2 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition">
                        Simpan Periode Seleksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
