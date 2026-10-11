<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ setting('institution_name', 'Pesantren Ell Firdaus') }} - SPK Santri FAM</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ setting_favicon_url() }}" type="{{ str_ends_with(setting_favicon_url(), '.svg') ? 'image/svg+xml' : 'image/x-icon' }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex bg-slate-50">
            <!-- Backdrop Mobile Sidebar -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false" 
                 class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
                 style="display: none;"></div>

            <!-- SIDEBAR: Enterprise Dark Slate with Emerald Highlights -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-200 border-r border-slate-800 transition-transform duration-200 ease-in-out flex flex-col justify-between">
                
                <div>
                    <!-- Brand Header (Synchronized) -->
                    <div class="h-16 flex items-center px-4 border-b border-slate-800/80 bg-slate-950/40">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                            @if (setting_logo_url())
                                <img src="{{ setting_logo_url() }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain bg-white/10 p-0.5 shrink-0">
                            @else
                                <div class="w-8 h-8 rounded-lg bg-emerald-700 text-white flex items-center justify-center font-bold text-xs tracking-wider shadow-sm shrink-0">
                                    {{ strtoupper(substr(setting('institution_name', 'EF'), 0, 2)) }}
                                </div>
                            @endif
                            <div class="overflow-hidden min-w-0">
                                <h1 class="text-xs font-bold tracking-tight text-white leading-tight truncate">
                                    {{ setting('institution_name', 'Pesantren Ell Firdaus') }}
                                </h1>
                                <p class="text-[10px] text-slate-400 truncate">
                                    {{ setting('institution_tagline', 'Kabupaten Labuhanbatu Selatan') }}
                                </p>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation Items -->
                    <nav class="px-3 py-4 space-y-5 overflow-y-auto max-h-[calc(100vh-140px)]">
                        <!-- Group 1: Navigasi Utama -->
                        <div>
                            <div class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Menu Utama
                            </div>
                            <div class="space-y-0.5">
                                <a href="{{ route('dashboard') }}" 
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <span>Dashboard</span>
                                </a>

                                <a href="{{ route('periods.index') }}" 
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('periods.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-4 h-4 {{ request()->routeIs('periods.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Periode Seleksi</span>
                                </a>

                                <a href="{{ route('candidates.index') }}" 
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('candidates.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-4 h-4 {{ request()->routeIs('candidates.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span>Data Calon Santri</span>
                                </a>
                            </div>
                        </div>

                        <!-- Group 2: Penilaian & Logika Fuzzy -->
                        <div>
                            <div class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Penilaian & Metode
                            </div>
                            <div class="space-y-0.5">
                                <a href="{{ route('scores.index') }}" 
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('scores.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-4 h-4 {{ request()->routeIs('scores.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Input Penilaian Santri</span>
                                </a>

                                <a href="{{ route('fuzzy.index') }}" 
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('fuzzy.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-4 h-4 {{ request()->routeIs('fuzzy.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                    <span>Konfigurasi Fuzzy & FAM</span>
                                </a>
                            </div>
                        </div>

                        <!-- Group 3: Hasil & Pelaporan -->
                        <div>
                            <div class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Hasil & Keputusan
                            </div>
                            <div class="space-y-0.5">
                                <a href="{{ route('selection-results.index') }}" 
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('selection-results.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-4 h-4 {{ request()->routeIs('selection-results.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span>Hasil Seleksi FAM</span>
                                </a>

                                <a href="{{ route('reports.index') }}" 
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('reports.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-4 h-4 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Laporan Seleksi</span>
                                </a>
                            </div>
                        </div>

                        <!-- Group 4: Sistem & Konfigurasi -->
                        <div>
                            <div class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Sistem
                            </div>
                            <div class="space-y-0.5">
                                <a href="{{ route('settings.index') }}" 
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('settings.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                    <svg class="w-4 h-4 {{ request()->routeIs('settings.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Pengaturan Website</span>
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Footer User Info -->
                <div class="p-3 border-t border-slate-800/80 bg-slate-950/40">
                    <div class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 flex items-center justify-center font-semibold text-xs shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden min-w-0 flex-1">
                            <p class="text-xs font-medium text-white truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                            <p class="text-[11px] text-slate-400 truncate">
                                {{ auth()->user()->isAdmin() ? 'Administrator' : 'Pimpinan' }}
                            </p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
                <!-- TOPBAR (Preserves Livewire Volt Navigation for Full Breeze Compatibility) -->
                <div class="sticky top-0 z-30 bg-white border-b border-slate-200/80">
                    <div class="flex items-center justify-between h-14 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <!-- Toggle Sidebar Mobile -->
                            <button @click="sidebarOpen = !sidebarOpen" 
                                    class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium text-slate-500">
                                    Pesantren Ell Firdaus Labusel
                                </span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                    Jenjang MA
                                </span>
                            </div>
                        </div>

                        <!-- Topbar Right: Breeze Livewire Navigation Component -->
                        <div class="flex items-center gap-3">
                            <livewire:layout.navigation />
                        </div>
                    </div>
                </div>

                <!-- Page Header (Optional) -->
                @if (isset($header))
                    <header class="bg-white border-b border-slate-200/80 py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </header>
                @endif

                <!-- Page Main Slot -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                    <!-- Flash Message Notifikasi -->
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" class="mb-5 flex items-center justify-between p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 text-sm leading-none">&times;</button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" class="mb-5 flex items-center justify-between p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                            <button @click="show = false" class="text-rose-600 hover:text-rose-800 text-sm leading-none">&times;</button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div x-data="{ show: true }" x-show="show" class="mb-5 p-3.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                            <div class="flex items-center gap-2 font-semibold mb-1.5">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Periksa input berikut:
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 pl-1 text-[11px]">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </main>

                <!-- Page Footer -->
                <footer class="border-t border-slate-200 py-4 px-4 sm:px-6 lg:px-8 text-center text-[11px] text-slate-500 bg-white">
                    &copy; {{ date('Y') }} SPK Penerimaan Santri Baru MA &bull; Metode Fuzzy Associative Memory (FAM) &bull; Pesantren Ell Firdaus Labusel
                </footer>
            </div>
        </div>
    </body>
</html>
