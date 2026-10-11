@props([
    'items' => [],
])

<nav class="flex items-center text-xs font-normal text-slate-500 mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1.5">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-700 transition">
                <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
        </li>
        @foreach ($items as $item)
            <li class="inline-flex items-center">
                <svg class="w-3 h-3 text-slate-300 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                @if (!empty($item['url']))
                    <a href="{{ $item['url'] }}" class="text-slate-500 hover:text-emerald-700 transition">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-slate-900 font-medium">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
