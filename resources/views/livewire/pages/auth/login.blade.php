<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-slate-800 tracking-tighter uppercase">Selamat Datang</h2>
        <p class="text-sm text-slate-500 font-medium">Silakan masuk ke akun Anugerah Ponsel Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <div>
            <label for="email" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Alamat Email</label>
            <input 
                wire:model="form.email" 
                id="email" 
                type="email" 
                name="email" 
                required 
                autofocus 
                class="block w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all duration-200"
                placeholder="nama@email.com"
            />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-1 ml-1">
                <label for="password" class="block text-xs font-black text-slate-400 uppercase tracking-widest">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black text-blue-600 hover:text-blue-700 uppercase tracking-tighter" href="{{ route('password.request') }}" wire:navigate>
                        Lupa Sandi?
                    </a>
                @endif
            </div>

            <input 
                wire:model="form.password" 
                id="password" 
                type="password" 
                name="password" 
                required 
                class="block w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all duration-200"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <input 
                wire:model="form.remember" 
                id="remember" 
                type="checkbox" 
                class="w-4 h-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 transition-all" 
                name="remember"
            >
            <label for="remember" class="ms-2 text-xs font-bold text-slate-500 uppercase tracking-tight italic select-none cursor-pointer">Ingat Sesi Saya</label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-0.5 transition-all duration-200 uppercase tracking-[0.2em] text-xs">
                Masuk ke Sistem
            </button>
        </div>
    </form>

    
</div>