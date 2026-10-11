@props([
    'phase' => 'Fase Pengembangan',
    'title' => 'Modul Sedang Disiapkan',
    'badge' => 'Dalam Pengerjaan',
    'description' => '',
    'features' => [],
])

<div class="bg-gradient-to-r from-purple-900/90 to-indigo-950 border border-purple-500/30 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden my-4">
    <!-- Background Glow Decoration -->
    <div class="absolute -top-12 -right-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-purple-600/20 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-purple-800/60 pb-5 mb-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-purple-600/30 border border-purple-400/30 flex items-center justify-center text-emerald-400 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-purple-300 bg-purple-950/80 px-2.5 py-0.5 rounded-full border border-purple-500/40">
                        {{ $phase }}
                    </span>
                    <span class="text-xs font-medium text-emerald-300 bg-emerald-950/80 px-2.5 py-0.5 rounded-full border border-emerald-500/40">
                        {{ $badge }}
                    </span>
                </div>
                <h3 class="text-xl font-bold text-white mt-1">{{ $title }}</h3>
            </div>
        </div>

        <div class="flex items-center gap-2 text-xs text-purple-200 bg-purple-800/40 px-3.5 py-2 rounded-xl border border-purple-600/30 self-start md:self-auto">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>Skema Database & Model Eloquent Sudah Aktif</span>
        </div>
    </div>

    @if ($description)
        <p class="text-sm text-purple-200/90 leading-relaxed max-w-3xl mb-4">{{ $description }}</p>
    @endif

    @if (!empty($features))
        <div class="mt-4 pt-4 border-t border-purple-800/50">
            <h4 class="text-xs font-semibold uppercase tracking-wider text-purple-300 mb-2">Fitur yang Dijadwalkan pada Fase Ini:</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                @foreach ($features as $feature)
                    <div class="flex items-start gap-2 text-xs text-purple-100 bg-purple-950/40 p-2.5 rounded-lg border border-purple-800/40">
                        <span class="text-emerald-400 font-bold mt-0.5">•</span>
                        <span>{{ $feature }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
