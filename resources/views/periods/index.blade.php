<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Periode Seleksi Santri</h2>
                <p class="text-xs text-slate-500 mt-0.5">Kelola gelombang dan tahun ajaran penerimaan santri baru MA</p>
            </div>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('periods.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-medium shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Periode Baru
                </a>
            @endif
        </div>
    </x-slot>

    <x-breadcrumb :items="[['label' => 'Periode Seleksi', 'url' => null]]" />

    <!-- Filter & Pencarian -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 shadow-sm mb-5">
        <form method="GET" action="{{ route('periods.index') }}" class="flex flex-col sm:flex-row items-center gap-2.5">
            <div class="relative flex-1 w-full">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nama periode atau tahun ajaran (contoh: 2026/2027)..." 
                       class="w-full text-xs rounded-lg border-slate-300 text-slate-900 pl-9 pr-4 py-2 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 placeholder-slate-400">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">
                Cari
            </button>
            @if ($search)
                <a href="{{ route('periods.index') }}" class="w-full sm:w-auto px-3.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-lg transition text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Periode Seleksi -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6" x-data="{ deleteModalOpen: false, deleteActionUrl: '', deleteItemName: '' }">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Daftar Periode Seleksi</h3>
            <span class="text-xs text-slate-500 font-normal">Total: {{ $periods->total() }} Periode</span>
        </div>

        @if ($periods->isEmpty())
            <x-empty-state 
                title="Tidak Ada Periode Seleksi"
                description="{{ $search ? 'Tidak ada periode seleksi yang cocok dengan pencarian Anda.' : 'Belum ada periode seleksi yang dibuat.' }}"
                :actionText="auth()->user()->isAdmin() && !$search ? 'Tambah Periode Sekarang' : null"
                :actionUrl="auth()->user()->isAdmin() && !$search ? route('periods.create') : null"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                        <tr>
                            <th class="py-3 px-4">Nama Periode</th>
                            <th class="py-3 px-4">Tahun Ajaran</th>
                            <th class="py-3 px-4">Tanggal Mulai</th>
                            <th class="py-3 px-4">Tanggal Selesai</th>
                            <th class="py-3 px-4 text-center">Santri Terdaftar</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($periods as $period)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4">
                                    <a href="{{ route('periods.show', $period) }}" class="font-medium text-slate-900 hover:text-emerald-700 transition">
                                        {{ $period->name }}
                                    </a>
                                    @if ($period->description)
                                        <p class="text-[11px] text-slate-500 truncate max-w-xs mt-0.5">{{ $period->description }}</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-700">
                                    {{ $period->academic_year }}
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-mono">
                                    {{ $period->start_date ? $period->start_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-mono">
                                    {{ $period->end_date ? $period->end_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">
                                    {{ $period->candidates_count }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($period->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('periods.show', $period) }}" 
                                           class="px-2.5 py-1 rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition"
                                           title="Lihat Detail">
                                            Detail
                                        </a>

                                        @if (auth()->user()->isAdmin())
                                            <!-- Toggle Aktif -->
                                            <form action="{{ route('periods.toggle-active', $period) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="px-2.5 py-1 rounded-md text-xs font-medium transition border {{ $period->is_active ? 'bg-amber-50 hover:bg-amber-100 text-amber-800 border-amber-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border-emerald-200' }}">
                                                    {{ $period->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>

                                            <!-- Edit -->
                                            <a href="{{ route('periods.edit', $period) }}" 
                                               class="px-2.5 py-1 rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium transition">
                                                Ubah
                                            </a>

                                            <!-- Hapus -->
                                            <button type="button" 
                                                    @click="deleteModalOpen = true; deleteActionUrl = @js(route('periods.destroy', $period)); deleteItemName = @js($period->name)"
                                                    class="px-2.5 py-1 rounded-md bg-white border border-rose-200 hover:bg-rose-50 text-rose-700 text-xs font-medium transition">
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
                {{ $periods->links() }}
            </div>
        @endif

        <!-- Modal Konfirmasi Hapus -->
        <div x-show="deleteModalOpen" 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
             style="display: none;">
            <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl border border-slate-200 text-left" @click.away="deleteModalOpen = false">
                <h4 class="text-sm font-bold text-slate-900">Konfirmasi Penghapusan Periode</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Apakah Anda yakin ingin menghapus periode <span class="font-semibold text-slate-800" x-text="deleteItemName"></span>? Tindakan ini tidak dapat dibatalkan jika periode belum memiliki pendaftar.
                </p>
                <div class="mt-5 flex justify-end gap-2.5">
                    <button type="button" @click="deleteModalOpen = false" class="px-3.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-medium transition">
                        Batal
                    </button>
                    <form :action="deleteActionUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-medium transition shadow-sm">
                            Ya, Hapus Periode
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
