<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Toko Anugerah Ponsel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @php
                // Kita normalisasi role di sini agar pengecekan di bawah stabil
                $currentRole = strtolower(trim(Auth::user()->role));
            @endphp

            {{-- ========================================================== --}}
            {{-- BAGIAN UNTUK RAFI (STAFF / OPERASIONAL) --}}
            {{-- ========================================================== --}}
            @if($currentRole === 'staff')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border-t-4 border-blue-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-blue-600">Halo {{ Auth::user()->name }}! (Menu Operasional)</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">Mode Kasir Aktif</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="p-4 bg-blue-50 rounded-lg shadow-sm border border-blue-100">
                            <h4 class="font-bold text-blue-800 text-sm">Total Produk di Sistem</h4>
                            <p class="text-2xl font-black text-blue-900">{{ \App\Models\Product::count() }} Item</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg shadow-sm border border-green-100">
                            <h4 class="font-bold text-green-800 text-sm">Status Transaksi</h4>
                            <p class="text-xs text-green-700 italic">Sistem siap mencatat penjualan baru.</p>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-lg shadow-sm border border-yellow-100">
                            <h4 class="font-bold text-yellow-800 text-sm">Monitoring Real-time</h4>
                            <p class="text-xs text-yellow-700 italic">Stok otomatis sinkron dengan gudang.</p>
                        </div>
                    </div>

                    {{-- Area Kasir --}}
                    <div class="mt-4 mb-8">
                        <h4 class="text-md font-semibold mb-4 text-gray-700 uppercase tracking-wider">🛒 Area Kasir Penjualan</h4>
                        <livewire:operational.cart />
                    </div>

                    <hr class="my-8 border-gray-200">

                    {{-- Bagian Inventaris Rafi --}}
                    <div class="mt-4 mb-8">
                        {{-- KASIH KEY AGAR TIDAK BENTROK --}}
                        <livewire:operational.product-list :key="'list-staff-' . Auth::id()" />
                    </div>

                    <hr class="my-8 border-dashed">

                    {{-- Fitur Pengeluaran --}}
                    <div class="mt-4">
                        <h4 class="text-md font-semibold mb-4 text-gray-700 uppercase tracking-wider">💸 Manajemen Pengeluaran</h4>
                        <livewire:operational.expense-manager />
                    </div>
                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- BAGIAN UNTUK BINTANG (MANAGER / BUSINESS INTELLIGENCE) --}}
            {{-- ========================================================== --}}
            @if($currentRole === 'manager')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border-t-4 border-purple-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-purple-600">Halo {{ Auth::user()->name }}! (Dashboard BI & Manager)</h3>
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded border border-purple-400">Analisis Data</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="p-4 bg-purple-50 rounded-lg shadow border-l-4 border-purple-500">
                            <h4 class="font-bold text-purple-800">Analisis Persediaan</h4>
                            <p class="text-sm text-purple-700">Memantau perputaran barang untuk strategi restock.</p>
                        </div>
                        <div class="p-4 bg-red-50 rounded-lg shadow border-l-4 border-red-500">
                            <h4 class="font-bold text-red-800">Alert Rekomendasi Restock</h4>
                            <p class="text-2xl font-bold text-red-600">
                                {{ \App\Models\Product::whereRaw('stock <= min_stock')->count() }} 
                                <span class="text-sm font-normal">Produk di bawah batas minimum</span>
                            </p>
                        </div>
                    </div>

                    {{-- Grafik Penjualan Bintang --}}
                    <div class="mb-8">
                        <h4 class="text-md font-semibold mb-4 text-gray-700 uppercase tracking-wider">📈 Tren Penjualan Real-time</h4>
                        <livewire:manager.sales-chart />
                    </div>

                    {{-- Data Tabel BI Bintang --}}
                    <div class="mt-4">
                        {{-- KASIH KEY BERBEDA UNTUK BINTANG --}}
                        <livewire:operational.product-list :key="'list-manager-' . Auth::id()" />
                    </div>
                </div>
            @endif

            {{-- FOOTER INFO --}}
            <div class="mt-4 text-center text-gray-400 text-xs">
                Sistem Terintegrasi Toko Anugerah Ponsel &copy; 2026 - Final Project Rafi & Bintang
            </div>
        </div>
    </div>
</x-app-layout>