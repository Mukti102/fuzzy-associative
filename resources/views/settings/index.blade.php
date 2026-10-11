<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Pengaturan Website & Identitas Lembaga</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola nama pesantren, logo, kontak, kop surat, dan pejabat penandatangan SK laporan</p>
            </div>
            @if (auth()->user()->isAdmin())
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        Akses Penuh (Administrator)
                    </span>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                        Mode Baca (Pimpinan)
                    </span>
                </div>
            @endif
        </div>
    </x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pengaturan Website', 'url' => null],
    ]" />

    <div class="max-w-5xl mx-auto my-6 space-y-6">
        @if (!auth()->user()->isAdmin())
            <div class="p-4 rounded-xl bg-slate-100 border border-slate-200 text-xs text-slate-700 flex items-start gap-3">
                <svg class="w-4 h-4 text-slate-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>Anda masuk dengan akun <strong>Pimpinan</strong>. Anda dapat melihat seluruh konfigurasi aktif, namun perubahan identitas lembaga hanya dapat dilakukan oleh <strong>Administrator</strong>.</p>
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: LOGO & IDENTITAS PESANTREN -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">
                        1
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Logo & Branding Pesantren</h3>
                        <p class="text-xs text-slate-500">Logo dan nama ini akan disinkronkan ke Topbar, Sidebar, Halaman Depan, Login, dan Kop Surat</p>
                    </div>
                </div>

                <!-- Logo Uploader & Preview -->
                <div class="mb-6 p-4 rounded-xl bg-slate-50/70 border border-slate-200 flex flex-col sm:flex-row items-center gap-6">
                    <div class="shrink-0 flex flex-col items-center">
                        <div class="w-24 h-24 rounded-xl border-2 border-dashed border-slate-300 bg-white flex items-center justify-center overflow-hidden shadow-sm">
                            @if ($settings['logo_path'] ?? null)
                                <img src="{{ asset($settings['logo_path']) }}" alt="Logo Pesantren" class="max-w-full max-h-full object-contain p-2">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-emerald-800 text-white flex items-center justify-center font-bold text-xl shadow-sm">
                                    {{ strtoupper(substr($settings['institution_name'] ?? 'EF', 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <span class="text-[10px] text-slate-400 mt-2 font-medium">Preview Logo Aktif</span>
                    </div>

                    <div class="flex-1 w-full text-xs">
                        <h4 class="font-bold text-slate-900 mb-1">Unggah Logo Baru</h4>
                        <p class="text-[11px] text-slate-500 mb-3">Format file yang didukung: PNG, JPG, WebP, SVG. Disarankan berlatar belakang transparan dengan resolusi minimal 200x200 px (Maks. 2MB).</p>

                        @if (auth()->user()->isAdmin())
                            <div class="flex flex-wrap items-center gap-3">
                                <input type="file" 
                                       name="logo" 
                                       id="logo" 
                                       accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                       class="text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                                
                                @if ($settings['logo_path'] ?? null)
                                    <label class="inline-flex items-center gap-1.5 text-rose-600 hover:text-rose-700 cursor-pointer text-xs font-semibold py-1">
                                        <input type="checkbox" name="remove_logo" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        Hapus logo kustom (Gunakan avatar bawaan)
                                    </label>
                                @endif
                            </div>
                            @error('logo')
                                <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        @else
                            <p class="text-[11px] text-slate-500 italic">Hanya administrator yang dapat mengunggah file logo baru.</p>
                        @endif
                    </div>
                </div>

                <!-- Favicon Uploader & Preview -->
                <div class="mb-6 p-4 rounded-xl bg-slate-50/70 border border-slate-200 flex flex-col sm:flex-row items-center gap-6">
                    <div class="shrink-0 flex flex-col items-center">
                        <div class="w-16 h-16 rounded-xl border border-slate-300 bg-white flex items-center justify-center overflow-hidden shadow-sm p-2">
                            <img src="{{ setting_favicon_url() }}" alt="Favicon" class="w-10 h-10 object-contain">
                        </div>
                        <span class="text-[10px] text-slate-400 mt-2 font-medium">Favicon Tab Browser</span>
                    </div>

                    <div class="flex-1 w-full text-xs">
                        <h4 class="font-bold text-slate-900 mb-1">Unggah Favicon Kustom (Opsional)</h4>
                        <p class="text-[11px] text-slate-500 mb-3">Ikon kecil yang tampil pada tab peramban/browser. Jika tidak diunggah, sistem otomatis menggunakan logo utama atau ikon vektor bawaan pesantren (Format: ICO, PNG, SVG, WebP, Maks. 1MB).</p>

                        @if (auth()->user()->isAdmin())
                            <div class="flex flex-wrap items-center gap-3">
                                <input type="file" 
                                       name="favicon" 
                                       id="favicon" 
                                       accept=".ico,image/png,image/svg+xml,image/webp,image/jpeg"
                                       class="text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                                
                                @if ($settings['favicon_path'] ?? null)
                                    <label class="inline-flex items-center gap-1.5 text-rose-600 hover:text-rose-700 cursor-pointer text-xs font-semibold py-1">
                                        <input type="checkbox" name="remove_favicon" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        Hapus favicon kustom
                                    </label>
                                @endif
                            </div>
                            @error('favicon')
                                <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        @else
                            <p class="text-[11px] text-slate-500 italic">Hanya administrator yang dapat mengunggah favicon.</p>
                        @endif
                    </div>
                </div>

                <!-- Input Identitas Lembaga -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="institution_name" class="block text-xs font-bold text-slate-700 mb-1">
                            Nama Pesantren / Lembaga <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="institution_name" 
                               id="institution_name" 
                               value="{{ old('institution_name', $settings['institution_name'] ?? 'Pesantren Ell Firdaus') }}" 
                               required
                               @disabled(!auth()->user()->isAdmin())
                               class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                        @error('institution_name')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="institution_tagline" class="block text-xs font-bold text-slate-700 mb-1">
                            Sub-nama / Lokasi Daerah
                        </label>
                        <input type="text" 
                               name="institution_tagline" 
                               id="institution_tagline" 
                               value="{{ old('institution_tagline', $settings['institution_tagline'] ?? 'Kabupaten Labuhanbatu Selatan, Sumatera Utara') }}" 
                               @disabled(!auth()->user()->isAdmin())
                               class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="institution_address" class="block text-xs font-bold text-slate-700 mb-1">
                            Alamat Lengkap Pesantren
                        </label>
                        <textarea name="institution_address" 
                                  id="institution_address" 
                                  rows="2" 
                                  @disabled(!auth()->user()->isAdmin())
                                  class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">{{ old('institution_address', $settings['institution_address'] ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="institution_phone" class="block text-xs font-bold text-slate-700 mb-1">
                            Nomor Telepon / WhatsApp Panitia
                        </label>
                        <input type="text" 
                               name="institution_phone" 
                               id="institution_phone" 
                               value="{{ old('institution_phone', $settings['institution_phone'] ?? '') }}" 
                               @disabled(!auth()->user()->isAdmin())
                               class="w-full text-xs font-mono rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                    </div>

                    <div>
                        <label for="institution_email" class="block text-xs font-bold text-slate-700 mb-1">
                            Email Resmi
                        </label>
                        <input type="email" 
                               name="institution_email" 
                               id="institution_email" 
                               value="{{ old('institution_email', $settings['institution_email'] ?? '') }}" 
                               @disabled(!auth()->user()->isAdmin())
                               class="w-full text-xs font-mono rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="institution_website" class="block text-xs font-bold text-slate-700 mb-1">
                            Alamat Website Lembaga
                        </label>
                        <input type="text" 
                               name="institution_website" 
                               id="institution_website" 
                               value="{{ old('institution_website', $settings['institution_website'] ?? '') }}" 
                               placeholder="https://ellfirdaus.ac.id"
                               @disabled(!auth()->user()->isAdmin())
                               class="w-full text-xs font-mono rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: FORMAT KOP SURAT LAPORAN & SK -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">
                        2
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Format Kop Surat & Dokumen Resmi</h3>
                        <p class="text-xs text-slate-500">Judul header yang dicetak pada lembar Surat Keputusan (SK) dan Berita Acara Kelulusan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="institution_letterhead_title" class="block text-xs font-bold text-slate-700 mb-1">
                            Nama Induk Yayasan / Pesantren (Baris 1 Kop)
                        </label>
                        <input type="text" 
                               name="institution_letterhead_title" 
                               id="institution_letterhead_title" 
                               value="{{ old('institution_letterhead_title', $settings['institution_letterhead_title'] ?? 'YAYASAN PONDOK PESANTREN ELL FIRDAUS') }}" 
                               @disabled(!auth()->user()->isAdmin())
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                    </div>

                    <div>
                        <label for="institution_letterhead_subtitle" class="block text-xs font-bold text-slate-700 mb-1">
                            Subjudul / Panitia Pelaksana (Baris 2 Kop)
                        </label>
                        <input type="text" 
                               name="institution_letterhead_subtitle" 
                               id="institution_letterhead_subtitle" 
                               value="{{ old('institution_letterhead_subtitle', $settings['institution_letterhead_subtitle'] ?? 'PANITIA PENERIMAAN SANTRI BARU (PSB)') }}" 
                               @disabled(!auth()->user()->isAdmin())
                               class="w-full text-xs font-semibold rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                    </div>
                </div>

                <!-- Preview Kop Surat -->
                <div>
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-2">Simulasi Tampilan Kop Surat Cetak:</span>
                    <div class="p-5 bg-slate-50/60 rounded-xl border border-slate-200 text-center">
                        <div class="max-w-2xl mx-auto flex items-center justify-center gap-4 pb-3 border-b-2 border-emerald-900">
                            @if ($settings['logo_path'] ?? null)
                                <img src="{{ asset($settings['logo_path']) }}" alt="Logo" class="w-14 h-14 object-contain">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-emerald-900 text-white flex items-center justify-center font-bold text-base shrink-0">
                                    {{ strtoupper(substr($settings['institution_name'] ?? 'EF', 0, 2)) }}
                                </div>
                            @endif
                            <div class="text-center">
                                <h4 class="text-sm font-extrabold uppercase text-slate-900 tracking-wide font-serif leading-tight">
                                    {{ $settings['institution_letterhead_title'] ?? 'YAYASAN PONDOK PESANTREN ELL FIRDAUS' }}
                                </h4>
                                <h5 class="text-xs font-bold text-emerald-800 uppercase tracking-widest mt-0.5">
                                    {{ $settings['institution_letterhead_subtitle'] ?? 'PANITIA PENERIMAAN SANTRI BARU (PSB)' }}
                                </h5>
                                <p class="text-[10px] text-slate-600 mt-0.5">
                                    {{ $settings['institution_address'] ?? 'Jl. Lintas Sumatera, Kab. Labuhanbatu Selatan' }} &bull; Telp: {{ $settings['institution_phone'] ?? '-' }} &bull; Email: {{ $settings['institution_email'] ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PEJABAT PENANDATANGAN LAPORAN -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">
                        3
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Pejabat Penandatangan Laporan & SK</h3>
                        <p class="text-xs text-slate-500">Nama dan identitas resmi yang tertera pada kolom tanda tangan dokumen pengesahan hasil seleksi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Kolom Kiri: Pimpinan Pesantren -->
                    <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200 space-y-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 block">
                            Pimpinan Pondok Pesantren
                        </span>
                        <div>
                            <label for="leader_name" class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                            <input type="text" 
                                   name="leader_name" 
                                   id="leader_name" 
                                   value="{{ old('leader_name', $settings['leader_name'] ?? 'H. Ahmad Fauzi, Lc., M.Pd.') }}" 
                                   @disabled(!auth()->user()->isAdmin())
                                   class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                        </div>
                        <div>
                            <label for="leader_title" class="block text-[11px] font-semibold text-slate-700 mb-1">Jabatan Resmi</label>
                            <input type="text" 
                                   name="leader_title" 
                                   id="leader_title" 
                                   value="{{ old('leader_title', $settings['leader_title'] ?? 'Pimpinan Pondok Pesantren') }}" 
                                   @disabled(!auth()->user()->isAdmin())
                                   class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                        </div>
                        <div>
                            <label for="leader_nip" class="block text-[11px] font-semibold text-slate-700 mb-1">NIP / NIY / Nomor Identitas</label>
                            <input type="text" 
                                   name="leader_nip" 
                                   id="leader_nip" 
                                   value="{{ old('leader_nip', $settings['leader_nip'] ?? 'NIP. 19780512 200604 1 003') }}" 
                                   @disabled(!auth()->user()->isAdmin())
                                   class="w-full text-xs font-mono rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                        </div>
                    </div>

                    <!-- Kolom Kanan: Ketua Panitia PSB -->
                    <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200 space-y-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-800 block">
                            Ketua Panitia Seleksi PSB
                        </span>
                        <div>
                            <label for="committee_chairman_name" class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                            <input type="text" 
                                   name="committee_chairman_name" 
                                   id="committee_chairman_name" 
                                   value="{{ old('committee_chairman_name', $settings['committee_chairman_name'] ?? 'Ust. Muhammad Ridwan, S.Pd.I.') }}" 
                                   @disabled(!auth()->user()->isAdmin())
                                   class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                        </div>
                        <div>
                            <label for="committee_chairman_title" class="block text-[11px] font-semibold text-slate-700 mb-1">Jabatan Resmi</label>
                            <input type="text" 
                                   name="committee_chairman_title" 
                                   id="committee_chairman_title" 
                                   value="{{ old('committee_chairman_title', $settings['committee_chairman_title'] ?? 'Ketua Panitia Seleksi PSB') }}" 
                                   @disabled(!auth()->user()->isAdmin())
                                   class="w-full text-xs rounded-lg border-slate-300 px-3 py-2 focus:border-emerald-600 focus:ring-emerald-600 disabled:bg-slate-100 disabled:text-slate-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Action -->
            @if (auth()->user()->isAdmin())
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan & Sinkronkan Perubahan
                    </button>
                </div>
            @endif
        </form>
    </div>
</x-app-layout>
