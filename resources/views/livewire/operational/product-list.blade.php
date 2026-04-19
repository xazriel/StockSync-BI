@php
    // KITA PAKSA CEK ID NYA DI SINI
    $isRafi = (auth()->id() == 1);
    $isBintang = (auth()->id() == 2);
@endphp

<div class="space-y-4">
    {{-- Notifikasi Sukses --}}
    @if (session()->has('message'))
        <div class="p-3 bg-green-500 text-white rounded-lg shadow-md text-sm font-bold animate-bounce">
            {{ session('message') }}
        </div>
    @endif

    {{-- Header & Fitur Import --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div>
            @if($isRafi)
                {{-- KHUSUS UNTUK RAFI --}}
                <h2 class="text-xl font-bold text-blue-600">📦 Daftar Inventaris Barang</h2>
                <p class="text-xs text-gray-500">Halo rafi, silakan update stok masuk operasional di sini.</p>
            @elseif($isBintang)
                {{-- KHUSUS UNTUK BINTANG --}}
                <h2 class="text-xl font-bold text-purple-600">📊 Manajemen Stok & Analisis BI</h2>
                <p class="text-xs text-gray-500">Halo bintang, berikut data perputaran barang untuk strategi toko.</p>
            @else
                <h2 class="text-xl font-bold text-gray-800">Daftar Produk</h2>
                <p class="text-xs text-gray-500">Data produk sistem Anugerah Ponsel.</p>
            @endif
        </div>

        {{-- Tombol Import: Hanya untuk Rafi (ID 1) --}}
        @if($isRafi)
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all shadow-sm gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Import Excel (Rafi)
                </button>

                <div x-show="open" x-transition @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50 p-4" style="display: none;">
                    <form wire:submit.prevent="importExcel">
                        <div class="mb-4">
                            <label class="block mb-2 text-xs font-bold text-gray-700 uppercase">Pilih File Master Produk</label>
                            <input type="file" wire:model="file_excel" class="block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-1">
                        </div>
                        <div class="flex justify-end items-center gap-2">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-xs font-semibold shadow-md">Proses</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    {{-- Tabel Utama --}}
    <div class="overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3">Nama Produk</th>
                    <th class="px-6 py-3 text-center border-l">Stok</th>
                    <th class="px-6 py-3 text-right border-l">Harga Jual</th>
                    
                    {{-- HEADER KHUSUS RAFI (AKSI) --}}
                    @if($isRafi)
                        <th class="px-6 py-3 text-center border-l bg-blue-50 text-blue-700">Opsi</th>
                    @endif

                    {{-- HEADER KHUSUS BINTANG --}}
                    @if($isBintang)
                        <th class="px-6 py-3 text-center border-l">Status</th>
                        <th class="px-6 py-3 text-center bg-purple-50 text-purple-700 border-l border-purple-100 font-bold">Analisis BI</th>
                        <th class="px-6 py-3 text-center bg-purple-50 text-purple-700 font-bold">Rekomendasi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                        <div class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $product->brand }} | {{ $product->sku }}</div>
                    </td>
                    <td class="px-6 py-4 text-center border-l">
                        <span class="{{ $product->stock <= $product->min_stock ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                            {{ $product->stock }} {{ $product->unit->short_name ?? 'Unit' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right border-l">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                    
                    {{-- KOLOM AKSI HANYA UNTUK RAFI --}}
                    @if($isRafi)
                        <td class="px-6 py-4 text-center border-l bg-blue-50/30">
                            <div class="flex justify-center gap-2">
                                <button wire:click="editProduct({{ $product->id }})" class="p-1.5 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                
                                <button wire:click="deleteProduct({{ $product->id }})" 
                                        onclick="confirm('Yakin ingin menghapus {{ $product->name }}?') || event.stopImmediatePropagation()"
                                        class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    @endif

                    {{-- DETAIL KHUSUS MANAGER --}}
                    @if($isBintang)
                        <td class="px-6 py-4 text-center border-l">
                            <span class="px-2 py-1 rounded text-[10px] font-bold border {{ $product->stock <= $product->min_stock ? 'bg-red-100 text-red-800 border-red-400' : 'bg-green-100 text-green-800 border-green-400' }}">
                                {{ $product->stock <= $product->min_stock ? 'RESTOCK' : 'AMAN' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center border-l border-purple-100">
                            @php
                                $st = $product->sales_status ?? 'Medium Moving';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $st }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-sm text-purple-700 border-l border-purple-50">
                            {{ $product->restock_recommendation > 0 ? '+'.$product->restock_recommendation : '-' }}
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
            <script>
    window.addEventListener('editProduct', event => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
        </table>
    </div>
</div>