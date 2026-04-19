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

<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-18 py-2">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 group">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 group-hover:scale-110 transition">
                            <span class="text-white font-black text-xl">A</span>
                        </div>
                        <div class="hidden md:block">
                            <h1 class="text-sm font-black text-slate-800 leading-none uppercase tracking-tighter">Anugerah</h1>
                            <p class="text-[10px] font-bold text-blue-600 leading-none uppercase tracking-widest">Ponsel System</p>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate class="text-sm font-bold uppercase tracking-widest">
                        {{ __('Overview') }}
                    </x-nav-link>
                    {{-- Tambahkan link lain di sini jika ada route baru nanti --}}
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                {{-- Role Badge --}}
                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black uppercase tracking-widest border border-slate-200">
                    {{ auth()->user()->role ?? 'User' }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-2 border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 hover:text-slate-800 focus:outline-none transition shadow-sm">
                            {{-- Avatar Circle --}}
                            <div class="w-8 h-8 bg-gradient-to-tr from-blue-600 to-blue-400 rounded-full flex items-center justify-center text-white text-xs font-black shadow-inner">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            
                            <div class="text-left hidden lg:block">
                                <p class="text-xs font-black leading-none" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"></p>
                                <p class="text-[9px] text-slate-400 font-medium leading-none mt-1">{{ auth()->user()->email }}</p>
                            </div>

                            <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akun Saya</p>
                        </div>
                        <x-dropdown-link :href="route('profile')" wire:navigate class="font-bold text-slate-600">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link class="text-red-600 font-bold">
                                {{ __('Log Out System') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-100 bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate class="font-bold">
                {{ __('Dashboard Overview') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-slate-100">
            <div class="px-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-black">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-bold text-base text-slate-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"></div>
                    <div class="font-medium text-sm text-slate-500">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link class="text-red-600">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>