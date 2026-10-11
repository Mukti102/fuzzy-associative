<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Data Calon Santri</h2>
                <p class="text-xs text-slate-500 mt-0.5">Master data calon santri baru jenjang Madrasah Aliyah (MA)</p>
            </div>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('candidates.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Daftarkan Calon Santri
                </a>
            @endif
        </div>
    </x-slot>

    <x-breadcrumb :items="[['label' => 'Data Calon Santri', 'url' => null]]" />

    <!-- Bar Pencarian & Multi-Filter -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 shadow-sm mb-5">
        <form method="GET" action="{{ route('candidates.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
            <!-- Search Text -->
            <div class="relative sm:col-span-2">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nomor pendaftaran, nama, atau asal sekolah..." 
                       class="w-full text-xs rounded-lg border-slate-300 text-slate-900 pl-9 pr-4 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 placeholder-slate-400">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Filter Periode -->
            <div>
                <select name="period_id" class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 px-3 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    <option value="">-- Semua Periode --</option>
                    @foreach ($periods as $p)
                        <option value="{{ $p->id }}" {{ $periodId == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->academic_year }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Jenis Kelamin & Tombol Submit -->
            <div class="flex items-center gap-2">
                <select name="gender" class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 px-3 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    <option value="">-- Semua Gender --</option>
                    <option value="L" {{ $gender === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ $gender === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>

                <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition shrink-0">
                    Filter
                </button>

                @if ($search || $periodId || $gender)
                    <a href="{{ route('candidates.index') }}" class="px-2.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-xs font-medium rounded-lg transition shrink-0" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Data Calon Santri -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6" x-data="{ deleteModalOpen: false, deleteActionUrl: '', deleteItemName: '' }">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Daftar Calon Santri MA</h3>
            <span class="text-xs text-slate-500 font-normal">Total: {{ $candidates->total() }} Santri</span>
        </div>

        @if ($candidates->isEmpty())
            <x-empty-state 
                title="Tidak Ada Data Calon Santri"
                description="{{ $search || $periodId || $gender ? 'Tidak ada data calon santri yang cocok dengan filter pencarian.' : 'Belum ada calon santri yang terdaftar.' }}"
                :actionText="auth()->user()->isAdmin() && !$search && !$periodId ? 'Daftarkan Santri Sekarang' : null"
                :actionUrl="auth()->user()->isAdmin() && !$search && !$periodId ? route('candidates.create') : null"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                        <tr>
                            <th class="py-3 px-4">No. Registrasi</th>
                            <th class="py-3 px-4">Nama Lengkap</th>
                            <th class="py-3 px-4 text-center">JK</th>
                            <th class="py-3 px-4">Periode</th>
                            <th class="py-3 px-4">Asal Sekolah</th>
                            <th class="py-3 px-4 text-center">Status Nilai</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($candidates as $candidate)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                    {{ $candidate->registration_number }}
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('candidates.show', $candidate) }}" class="font-medium text-slate-900 hover:text-emerald-700 transition">
                                        {{ $candidate->name }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-center font-medium text-slate-600">
                                    {{ $candidate->gender === 'L' ? 'L' : 'P' }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $candidate->period->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-slate-500">
                                    {{ $candidate->school_origin ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($candidate->score && $candidate->score->is_complete)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Lengkap
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            Belum Lengkap
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('candidates.show', $candidate) }}" 
                                           class="px-2.5 py-1 rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition"
                                           title="Lihat Detail Profil">
                                            Detail
                                        </a>

                                        @if (auth()->user()->isAdmin())
                                            <a href="{{ route('scores.edit', $candidate) }}" 
                                               class="px-2.5 py-1 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-medium transition"
                                               title="Input / Edit Nilai 4 Kriteria">
                                                Nilai
                                            </a>

                                            <a href="{{ route('candidates.edit', $candidate) }}" 
                                               class="px-2.5 py-1 rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition"
                                               title="Ubah Biodata">
                                                Ubah
                                            </a>

                                            <button type="button" 
                                                    @click="deleteModalOpen = true; deleteActionUrl = @js(route('candidates.destroy', $candidate)); deleteItemName = @js($candidate->name . ' (' . $candidate->registration_number . ')')"
                                                    class="px-2.5 py-1 rounded-md bg-white border border-rose-200 hover:bg-rose-50 text-rose-700 text-xs font-medium transition"
                                                    title="Hapus Data Santri">
                                                Hapus
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3.5 border-t border-slate-100">
                {{ $candidates->links() }}
            </div>
        @endif

        <!-- Modal Konfirmasi Hapus Calon Santri -->
        <div x-show="deleteModalOpen" 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
             style="display: none;">
            <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl border border-slate-200 text-left" @click.away="deleteModalOpen = false">
                <h4 class="text-sm font-bold text-slate-900">Konfirmasi Penghapusan Santri</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Apakah Anda yakin ingin menghapus data calon santri <span class="font-semibold text-slate-800" x-text="deleteItemName"></span>? Seluruh catatan nilai santri ini juga akan terhapus.
                </p>
                <div class="mt-5 flex justify-end gap-2.5">
                    <button type="button" @click="deleteModalOpen = false" class="px-3.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-medium transition">
                        Batal
                    </button>
                    <form :action="deleteActionUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-medium transition shadow-sm">
                            Ya, Hapus Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
