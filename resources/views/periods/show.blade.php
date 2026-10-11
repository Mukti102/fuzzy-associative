<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">{{ $period->name }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tahun Ajaran: <span class="font-semibold text-slate-700">{{ $period->academic_year }}</span></p>
            </div>
            <div class="flex items-center gap-2">
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('periods.edit', $period) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-sm transition">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Ubah Periode
                    </a>
                    <a href="{{ route('candidates.create', ['period_id' => $period->id]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Daftarkan Santri
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <x-breadcrumb :items="[
        ['label' => 'Periode Seleksi', 'url' => route('periods.index')],
        ['label' => $period->name, 'url' => null],
    ]" />

    <!-- 4 Info Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 my-6">
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Status Gelombang</p>
            <div class="mt-2 flex items-center gap-2">
                @if ($period->is_active)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                        AKTIF / BUKA
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                        Nonaktif / Tutup
                    </span>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Rentang Pendaftaran</p>
            <p class="text-xs font-bold text-slate-900 mt-2">
                {{ $period->start_date ? $period->start_date->format('d M Y') : '-' }} s/d {{ $period->end_date ? $period->end_date->format('d M Y') : '-' }}
            </p>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Ambang Kelulusan (Passing Grade)</p>
            <p class="text-lg font-bold text-emerald-800 font-mono mt-1">
                {{ $period->passing_grade ? number_format($period->passing_grade, 2) : 'Belum Ditentukan' }}
            </p>
        </div>

        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-sm">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Kapasitas Kuota / Pendaftar</p>
            <p class="text-lg font-bold text-slate-900 mt-1">
                {{ $period->candidates_count }} <span class="text-xs font-normal text-slate-500">/ {{ $period->quota ? $period->quota . ' santri' : 'Tidak Terbatas' }}</span>
            </p>
        </div>
    </div>

    <!-- Tabel Santri dalam Periode Ini -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden my-6">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Calon Santri Terdaftar Pada Periode Ini</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar santri yang terikat pada gelombang pendaftaran {{ $period->name }}</p>
            </div>
            <span class="text-xs font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded-md">Total: {{ $candidates->total() }} Santri</span>
        </div>

        @if ($candidates->isEmpty())
            <x-empty-state 
                title="Belum Ada Santri Terdaftar"
                description="Periode ini belum memiliki calon santri yang didaftarkan."
                :actionText="auth()->user()->isAdmin() ? 'Daftarkan Calon Santri Sekarang' : null"
                :actionUrl="auth()->user()->isAdmin() ? route('candidates.create', ['period_id' => $period->id]) : null"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 text-slate-600 uppercase tracking-wider font-semibold border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">No. Pendaftaran</th>
                            <th class="py-3 px-4">Nama Lengkap</th>
                            <th class="py-3 px-4">Jenis Kelamin</th>
                            <th class="py-3 px-4">Asal Sekolah</th>
                            <th class="py-3 px-4 text-center">Status Kelengkapan Nilai</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach ($candidates as $candidate)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">{{ $candidate->registration_number }}</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-900">{{ $candidate->name }}</td>
                                <td class="py-3.5 px-4">{{ $candidate->gender_label }}</td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $candidate->school_origin ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if ($candidate->score && $candidate->score->is_complete)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            Lengkap (4 Kriteria)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                            Belum Lengkap
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('candidates.show', $candidate) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition">
                                        Detail Santri &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $candidates->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
