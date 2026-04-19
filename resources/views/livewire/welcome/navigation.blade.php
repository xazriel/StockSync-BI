<nav class="flex flex-1 justify-end items-center gap-4">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="group relative inline-flex items-center px-6 py-2.5 text-sm font-black tracking-widest text-white transition-all duration-200 bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none shadow-lg shadow-blue-200"
        >
            <span>MASUK KE DASHBOARD</span>
            <svg class="w-4 h-4 ms-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="text-sm font-bold tracking-widest text-slate-600 hover:text-blue-600 transition-colors uppercase"
        >
            {{ __('Log in') }}
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="inline-flex items-center px-5 py-2.5 text-sm font-black tracking-widest text-blue-600 transition-all duration-200 bg-white border-2 border-blue-600 rounded-xl hover:bg-blue-50 focus:outline-none uppercase shadow-sm"
            >
                {{ __('Register') }}
            </a>
        @endif
    @endauth
</nav>