<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
