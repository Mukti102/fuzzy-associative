<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex items-center gap-3">
    <!-- User Settings Dropdown -->
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-slate-200 text-xs font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition shadow-sm">
                <!-- User Avatar Dot -->
                <span class="w-5 h-5 rounded-md bg-emerald-700 text-white flex items-center justify-center font-bold text-[10px]">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </span>
                
                <span x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name" class="font-medium text-slate-800"></span>

                <svg class="fill-current h-3.5 w-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="px-3.5 py-2 border-b border-slate-100">
                <p class="text-xs font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                <div class="mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ auth()->user()->isAdmin() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        {{ auth()->user()->isAdmin() ? 'Panitia / Admin' : 'Pimpinan Pesantren' }}
                    </span>
                </div>
            </div>

            <x-dropdown-link :href="route('profile')" wire:navigate class="text-xs text-slate-700">
                {{ __('Profil Saya') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <button wire:click="logout" class="w-full text-start">
                <x-dropdown-link class="text-xs text-rose-600">
                    {{ __('Keluar (Log Out)') }}
                </x-dropdown-link>
            </button>
        </x-slot>
    </x-dropdown>
</div>
