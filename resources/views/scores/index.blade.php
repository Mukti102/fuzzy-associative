<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Penilaian 4 Kriteria Seleksi MA</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pengelolaan nilai Akademik, BTQ, Tahfidz, dan Wawancara calon santri</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-mono font-medium bg-slate-100 text-slate-700 border border-slate-200">
                    Skala: 0 — 100
                </span>
            </div>
        </div>
    </x-slot>

    <x-breadcrumb :items="[['label' => 'Penilaian Santri', 'url' => null]]" />

    <!-- Pencarian & Filter Status Kelengkapan -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 shadow-sm mb-5">
        <form method="GET" action="{{ route('scores.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
            <!-- Search Text -->
            <div class="relative sm:col-span-2">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nomor pendaftaran atau nama santri..." 
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

            <!-- Filter Kelengkapan Nilai -->
            <div class="flex items-center gap-2">
                <select name="status" class="w-full text-xs rounded-lg border-slate-300 text-slate-900 py-2 px-3 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    <option value="">-- Semua Status Nilai --</option>
                    <option value="lengkap" {{ $status === 'lengkap' ? 'selected' : '' }}>Lengkap (4 Nilai)</option>
                    <option value="belum_lengkap" {{ $status === 'belum_lengkap' ? 'selected' : '' }}>Belum Lengkap</option>
                </select>

                <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition shrink-0">
                    Filter
                </button>

                @if ($search || $periodId || $status)
                    <a href="{{ route('scores.index') }}" class="px-2.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-600 text-xs font-medium rounded-lg transition shrink-0" title="Reset">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Nilai Santri -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Daftar Nilai Calon Santri</h3>
            <span class="text-xs text-slate-500 font-normal">Total: {{ $candidates->total() }} Santri</span>
        </div>

        @if ($candidates->isEmpty())
            <x-empty-state 
                title="Tidak Ada Calon Santri"
                description="{{ $search || $periodId || $status ? 'Tidak ditemukan data santri dengan kriteria filter tersebut.' : 'Belum ada calon santri yang terdaftar.' }}"
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/70 text-[11px]">
                        <tr>
                            <th class="py-3 px-4">No. Pendaftaran</th>
                            <th class="py-3 px-4">Nama Lengkap</th>
                            <th class="py-3 px-4">Periode</th>
                            <th class="py-3 px-3 text-center">Akademik</th>
                            <th class="py-3 px-3 text-center">Tes BTQ</th>
                            <th class="py-3 px-3 text-center">Tahfidz</th>
                            <th class="py-3 px-3 text-center">Wawancara</th>
                            <th class="py-3 px-4 text-center">Kelengkapan</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($candidates as $candidate)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                    <a href="{{ route('candidates.show', $candidate) }}" class="hover:text-emerald-700 transition">
                                        {{ $candidate->registration_number }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-800">
                                    <a href="{{ route('candidates.show', $candidate) }}" class="hover:text-emerald-700 transition">
                                        {{ $candidate->name }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $candidate->period->name ?? '-' }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-medium text-slate-800">
                                    {{ $candidate->score?->academic_score !== null ? number_format($candidate->score->academic_score, 1) : '-' }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-medium text-slate-800">
                                    {{ $candidate->score?->btq_score !== null ? number_format($candidate->score->btq_score, 1) : '-' }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-medium text-slate-800">
                                    {{ $candidate->score?->tahfidz_score !== null ? number_format($candidate->score->tahfidz_score, 1) : '-' }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono font-medium text-slate-800">
                                    {{ $candidate->score?->interview_score !== null ? number_format($candidate->score->interview_score, 1) : '-' }}
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
                                    @if (auth()->user()->isAdmin())
                                        <a href="{{ route('scores.edit', $candidate) }}" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-medium transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Input / Ubah
                                        </a>
                                    @else
                                        <a href="{{ route('candidates.show', $candidate) }}" class="text-xs text-slate-600 font-medium hover:text-emerald-700 transition">
                                            Detail &rarr;
                                        </a>
                                    @endif
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
    </div>
</x-app-layout>
