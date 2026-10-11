<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ setting('institution_name', 'Pesantren Ell Firdaus') }} - Masuk ke Sistem</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ setting_favicon_url() }}" type="{{ str_ends_with(setting_favicon_url(), '.svg') ? 'image/svg+xml' : 'image/x-icon' }}">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full font-sans text-slate-800 antialiased bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <a href="/" wire:navigate class="inline-flex flex-col items-center group">
                @if (setting_logo_url())
                    <img src="{{ setting_logo_url() }}" alt="Logo" class="w-14 h-14 rounded-xl object-contain shadow-sm bg-white p-1 border border-slate-200">
                @else
                    <div class="w-12 h-12 rounded-xl bg-emerald-800 text-white flex items-center justify-center font-bold text-lg shadow-sm group-hover:bg-emerald-900 transition">
                        {{ strtoupper(substr(setting('institution_name', 'EF'), 0, 2)) }}
                    </div>
                @endif
                <h1 class="mt-3 text-lg font-bold text-slate-900 tracking-tight">{{ setting('institution_name', 'Pesantren Ell Firdaus') }}</h1>
                <p class="text-xs text-slate-500 font-medium">{{ setting('institution_tagline', 'Sistem Pendukung Keputusan Penerimaan Santri Baru (FAM)') }}</p>
            </a>
        </div>

        <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
            <div class="bg-white py-8 px-6 sm:px-8 rounded-xl border border-slate-200/80 shadow-sm">
                {{ $slot }}
            </div>
            <p class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} {{ setting('institution_name', 'Pesantren Ell Firdaus') }}. Hak Cipta Dilindungi.
            </p>
        </div>
    </body>
</html>
