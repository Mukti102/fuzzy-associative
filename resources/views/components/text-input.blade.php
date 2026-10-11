@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 rounded-lg text-slate-900 text-xs shadow-sm']) }}>
