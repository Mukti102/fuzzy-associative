<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 tracking-tight">Pendaftaran Calon Santri Baru</h2>
        <p class="text-xs text-slate-500 mt-0.5">Formulir pendaftaran santri baru Madrasah Aliyah Pesantren Ell Firdaus Labusel</p>
    </x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Data Calon Santri', 'url' => route('candidates.index')],
        ['label' => 'Pendaftaran Santri Baru', 'url' => null],
    ]" />

    <div class="max-w-2xl mx-auto my-5">
        <div class="bg-white rounded-xl border border-slate-200/80 p-5 sm:p-7 shadow-sm">
            <form action="{{ route('candidates.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Periode Seleksi -->
                <div>
                    <label for="selection_period_id" class="block text-xs font-semibold text-slate-700 mb-1">
                        Periode Seleksi <span class="text-rose-500">*</span>
                    </label>
                    <select id="selection_period_id" 
                            name="selection_period_id" 
                            required 
                            class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('selection_period_id') border-rose-500 @enderror">
                        @foreach ($periods as $p)
                            <option value="{{ $p->id }}" {{ old('selection_period_id', request('period_id', $activePeriod?->id)) == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->academic_year }}) {{ $p->is_active ? '— [AKTIF]' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('selection_period_id')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Pendaftaran & Nama Lengkap -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="registration_number" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nomor Pendaftaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="registration_number" 
                               name="registration_number" 
                               value="{{ old('registration_number') }}" 
                               required 
                               placeholder="Contoh: PSB-MA-2026-001"
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('registration_number') border-rose-500 @enderror">
                        @error('registration_number')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Lengkap Santri <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="Nama lengkap calon santri"
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('name') border-rose-500 @enderror">
                        @error('name')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Jenis Kelamin & Asal Sekolah -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="gender" class="block text-xs font-semibold text-slate-700 mb-1">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <select id="gender" 
                                name="gender" 
                                required 
                                class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('gender') border-rose-500 @enderror">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki (Santriwan)</option>
                            <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan (Santriwati)</option>
                        </select>
                        @error('gender')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="school_origin" class="block text-xs font-semibold text-slate-700 mb-1">
                            Asal Sekolah / Madrasah
                        </label>
                        <input type="text" 
                               id="school_origin" 
                               name="school_origin" 
                               value="{{ old('school_origin') }}" 
                               placeholder="Contoh: MTs / SMP Negeri 1 Kotapinang"
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('school_origin') border-rose-500 @enderror">
                        @error('school_origin')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tempat & Tanggal Lahir -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="birth_place" class="block text-xs font-semibold text-slate-700 mb-1">
                            Tempat Lahir
                        </label>
                        <input type="text" 
                               id="birth_place" 
                               name="birth_place" 
                               value="{{ old('birth_place') }}" 
                               placeholder="Contoh: Kotapinang"
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('birth_place') border-rose-500 @enderror">
                        @error('birth_place')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-xs font-semibold text-slate-700 mb-1">
                            Tanggal Lahir
                        </label>
                        <input type="date" 
                               id="birth_date" 
                               name="birth_date" 
                               value="{{ old('birth_date') }}" 
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('birth_date') border-rose-500 @enderror">
                        @error('birth_date')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Telepon / WhatsApp -->
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nomor Telepon / WhatsApp Orang Tua / Wali
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           placeholder="Contoh: 081234567890"
                           class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('phone') border-rose-500 @enderror">
                    @error('phone')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat Domisili -->
                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-700 mb-1">
                        Alamat Domisili Santri
                    </label>
                    <textarea id="address" 
                              name="address" 
                              rows="3" 
                              placeholder="Alamat lengkap calon santri..."
                              class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('address') border-rose-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-2 pt-3.5 border-t border-slate-100">
                    <a href="{{ route('candidates.index') }}" class="px-3.5 py-2 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition">
                        Daftarkan Calon Santri
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
