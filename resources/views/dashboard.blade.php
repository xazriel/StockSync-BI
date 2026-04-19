<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-tighter">
                {{ __('Dashboard Utama') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @php
                // Normalisasi role agar pengecekan stabil
                $currentRole = strtolower(trim(Auth::user()->role));
            @endphp

            {{-- ========================================================== --}}
            {{-- BAGIAN UNTUK RAFI (STAFF / OPERASIONAL) --}}
            {{-- ========================================================== --}}
            @if($currentRole === 'staff')
                <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-[2rem] p-8 mb-8 relative">
                    {{-- Decorative Accent --}}
                    <div class="absolute top-0 left-0 w-full h-2 bg-blue-600"></div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                        <div>
                            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">
                                Hi, <span class="text-blue-600">{{ Auth::user()->name }}</span>!
                            </h3>
                            <p class="text-sm text-slate-500 font-medium">Panel Kendali Operasional Toko Anugerah Ponsel.</p>
                        </div>                            
                    </div>

                    {{-- NEW: DASHBOARD MONITORING & AKSI CEPAT (RAFI) --}}
                    <div class="mb-10">
                        <livewire:operational.operational-dashboard />
                    </div>

                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-100"></div>
                        </div>
                    </div>

                    {{-- STATIK INFO CARDS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 transition hover:border-blue-200">
                            <h4 class="font-black text-slate-400 text-[10px] uppercase tracking-[0.2em] mb-2">Total Produk</h4>
                            <p class="text-3xl font-black text-slate-800">{{ \App\Models\Product::count() }} <span class="text-sm font-bold text-slate-400">Unit</span></p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 transition hover:border-green-200">
                            <h4 class="font-black text-slate-400 text-[10px] uppercase tracking-[0.2em] mb-2">Status Kasir</h4>
                            <p class="text-sm font-bold text-green-600 italic">Siap melayani transaksi baru.</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 transition hover:border-yellow-200">
                            <h4 class="font-black text-slate-400 text-[10px] uppercase tracking-[0.2em] mb-2">Sinkronisasi</h4>
                            <p class="text-sm font-bold text-slate-600">Otomatis Terhubung ke DB.</p>
                        </div>
                    </div>

                    {{-- SECTIONS DENGAN JUDUL MODERN --}}
                    <div class="space-y-16">
                        {{-- SECTION 1: INPUT PRODUK --}}
                        <section>
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <h4 class="text-sm font-black text-slate-700 uppercase tracking-[0.2em]">Manajemen Produk</h4>
                            </div>
                            <livewire:operational.product-manager />
                        </section>

                        {{-- SECTION 2: SATUAN --}}
                        <section>
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <h4 class="text-sm font-black text-slate-700 uppercase tracking-[0.2em]">Pengaturan Satuan</h4>
                            </div>
                            <livewire:operational.unit-manager />
                        </section>

                        {{-- SECTION 3: AREA KASIR --}}
                        <section class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-blue-900/20">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-black uppercase tracking-tighter">Terminal Kasir</h4>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Anugerah Ponsel POS v1.0</p>
                                </div>
                            </div>
                            <livewire:operational.cart />
                        </section>

                        {{-- SECTION 4: DAFTAR INVENTARIS --}}
                        <section>
                            <h4 class="text-sm font-black text-slate-700 uppercase tracking-[0.2em] mb-6 px-2">📦 Daftar Stok Inventaris</h4>
                            <livewire:operational.product-list :key="'list-staff-' . Auth::id()" />
                        </section>

                        {{-- SECTION 5: MANAJEMEN PENGELUARAN --}}
                        <section class="bg-red-50/50 p-8 rounded-[2rem] border border-red-100">
                            <h4 class="text-sm font-black text-red-700 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                Catat Pengeluaran Toko
                            </h4>
                            <livewire:operational.expense-manager />
                        </section>
                    </div>
                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- BAGIAN UNTUK BINTANG (MANAGER / BUSINESS INTELLIGENCE) --}}
            {{-- ========================================================== --}}
            @if($currentRole === 'manager')
                <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-[2rem] p-8 mb-8 relative">
                    {{-- Decorative Accent --}}
                    <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>

                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">
                                Executive Panel, <span class="text-indigo-600">{{ Auth::user()->name }}</span>
                            </h3>
                            <p class="text-sm text-slate-500 font-medium">Business Intelligence & Strategic Monitoring.</p>
                        </div>
                        <span class="bg-indigo-600 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-indigo-200">
                            Analisis Data
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <div class="p-8 bg-indigo-50 rounded-[2rem] border border-indigo-100 relative overflow-hidden group">
                            <div class="relative z-10">
                                <h4 class="font-black text-indigo-800 text-xs uppercase tracking-[0.2em] mb-4">Analisis Persediaan</h4>
                                <p class="text-sm text-indigo-600/80 font-bold leading-relaxed">
                                    Memantau perputaran barang untuk optimalisasi restock dan manajemen vendor.
                                </p>
                            </div>
                            <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-indigo-100 transform group-hover:scale-110 transition duration-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                        </div>

                        <div class="p-8 bg-white rounded-[2rem] border-2 border-red-100 relative group transition hover:bg-red-50">
                            <h4 class="font-black text-red-500 text-xs uppercase tracking-[0.2em] mb-4 text-center md:text-left">Rekomendasi Restock</h4>
                            <div class="flex items-baseline justify-center md:justify-start gap-3">
                                <p class="text-5xl font-black text-red-600 tracking-tighter">
                                    {{ \App\Models\Product::whereRaw('stock <= min_stock')->count() }} 
                                </p>
                                <span class="text-sm font-black text-red-400 uppercase tracking-widest">Produk Menipis</span>
                            </div>
                        </div>
                    </div>

                    {{-- Grafik Penjualan Bintang --}}
                    <div class="mb-12 bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 shadow-inner">
                        <div class="flex items-center justify-between mb-8 px-2">
                            <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.3em]">📈 Tren Penjualan Real-time</h4>
                            
                        </div>
                        <livewire:manager.sales-chart />
                    </div>

                    {{-- Data Tabel BI Bintang --}}
                    <div class="mt-4">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.3em] mb-8 px-2">📋 Detail Performa Produk</h4>
                        <livewire:operational.product-list :key="'list-manager-' . Auth::id()" />
                    </div>
                </div>
            @endif

            {{-- FOOTER INFO --}}
            <div class="mt-16 text-center">
                <div class="inline-block px-6 py-2 bg-white border border-slate-200 rounded-full shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 tracking-[0.3em] uppercase">
                        Sistem Terintegrasi Toko Anugerah Ponsel &copy; 2026
                    </p>
                </div>
                <div class="mt-4 flex justify-center gap-8 items-center opacity-40">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Rafi (Operasional)</p>
                    <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Bintang (Business Intelligence)</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>