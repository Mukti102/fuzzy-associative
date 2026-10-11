@props([
    'title' => '',
    'value' => '0',
    'subtitle' => null,
    'color' => 'slate', // 'emerald', 'amber', 'rose', 'slate'
    'icon' => null,
])

@php
    $colorClasses = [
        'emerald' => 'text-emerald-700 bg-emerald-50 border-emerald-100',
        'amber' => 'text-amber-700 bg-amber-50 border-amber-100',
        'rose' => 'text-rose-700 bg-rose-50 border-rose-100',
        'slate' => 'text-slate-700 bg-slate-50 border-slate-100',
        'purple' => 'text-slate-700 bg-slate-50 border-slate-100',
    ][$color] ?? 'text-slate-700 bg-slate-50 border-slate-100';
@endphp

<div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-sm hover:border-slate-300 transition">
    <div class="flex items-start justify-between gap-3">
        <div class="space-y-1">
            <p class="text-[11px] font-medium uppercase tracking-wider text-slate-500">{{ $title }}</p>
            <h4 class="text-2xl font-bold tracking-tight text-slate-900 font-mono">{{ $value }}</h4>
            @if ($subtitle)
                <p class="text-[11px] text-slate-500 flex items-center gap-1">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        @if ($icon)
            <div class="w-9 h-9 rounded-lg {{ $colorClasses }} border flex items-center justify-center shrink-0">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
