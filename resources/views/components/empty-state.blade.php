@props([
    'title' => 'Belum Ada Data',
    'description' => 'Data untuk modul ini belum tersedia di dalam sistem.',
    'actionText' => null,
    'actionUrl' => null,
])

<div class="bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 p-10 text-center flex flex-col items-center justify-center my-4">
    <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 flex items-center justify-center mb-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
    </div>
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ $title }}</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mb-5 leading-relaxed">{{ $description }}</p>
    @if ($actionText && $actionUrl)
        <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold rounded-xl shadow-md transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ $actionText }}
        </a>
    @endif
</div>
