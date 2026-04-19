<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Anugerah Ponsel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                letter-spacing: -0.01em;
            }
            /* Menghilangkan scrollbar default yang kasar di beberapa browser */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f5f9;
            }
            ::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>
    </head>
    <body class="antialiased text-slate-900 selection:bg-blue-100 selection:text-blue-700">
        {{-- Background semi-abu yang lebih cerah (Slate 50) --}}
        <div class="min-h-screen bg-[#f8fafc]">
            
            {{-- Navigation: Pastikan ini nanti kita poles juga --}}
            <livewire:layout.navigation />

            {{-- Header Section: Dibuat lebih menyatu dengan background --}}
            @if (isset($header))
                <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200/60 shadow-sm">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- Main Content --}}
            <main class="relative">
                {{ $slot }}
            </main>
        </div>

        {{-- Script Stack untuk integrasi Chart.js & Livewire --}}
        @stack('scripts')
        
        <script>
            // Script global tambahan jika diperlukan untuk UI
            document.addEventListener('livewire:navigated', () => {
                console.log('Anugerah Ponsel System: Ready');
            });
        </script>
    </body>
</html>