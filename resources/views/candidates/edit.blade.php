<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-900 tracking-tight">Ubah Biodata Calon Santri</h2>
        <p class="text-xs text-slate-500 mt-0.5">Perbarui data profil {{ $candidate->name }} ({{ $candidate->registration_number }})</p>
    </x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Data Calon Santri', 'url' => route('candidates.index')],
        ['label' => $candidate->name, 'url' => route('candidates.show', $candidate)],
        ['label' => 'Ubah Biodata', 'url' => null],
    ]" />

    <div class="max-w-2xl mx-auto my-5">
        <div class="bg-white rounded-xl border border-slate-200/80 p-5 sm:p-7 shadow-sm">
            <form action="{{ route('candidates.update', $candidate) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

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
                            <option value="{{ $p->id }}" {{ old('selection_period_id', $candidate->selection_period_id) == $p->id ? 'selected' : '' }}>
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
                               value="{{ old('registration_number', $candidate->registration_number) }}" 
                               required 
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
                               value="{{ old('name', $candidate->name) }}" 
                               required 
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
                            <option value="L" {{ old('gender', $candidate->gender) === 'L' ? 'selected' : '' }}>Laki-laki (Santriwan)</option>
                            <option value="P" {{ old('gender', $candidate->gender) === 'P' ? 'selected' : '' }}>Perempuan (Santriwati)</option>
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
                               value="{{ old('school_origin', $candidate->school_origin) }}" 
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
                               value="{{ old('birth_place', $candidate->birth_place) }}" 
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
                               value="{{ old('birth_date', $candidate->birth_date ? $candidate->birth_date->format('Y-m-d') : '') }}" 
                               class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('birth_date') border-rose-500 @enderror">
                        @error('birth_date')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Telepon / WhatsApp -->
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nomor Telepon / WhatsApp
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $candidate->phone) }}" 
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
                              class="w-full text-xs rounded-lg border-slate-300 text-slate-900 px-3 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 @error('address') border-rose-500 @enderror">{{ old('address', $candidate->address) }}</textarea>
                    @error('address')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-2 pt-3.5 border-t border-slate-100">
                    <a href="{{ route('candidates.show', $candidate) }}" class="px-3.5 py-2 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition">
                        Perbarui Data Santri
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
