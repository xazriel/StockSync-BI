<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <div class="p-2 bg-blue-600 rounded-lg text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 uppercase tracking-tight">Penerimaan & Stok Barang (Rafi)</h3>
                <p class="text-xs text-gray-400 font-medium">Ketik nama/merek HP untuk tambah stok, atau isi form untuk unit baru.</p>
            </div>
        </div>

        {{-- Status deteksi otomatis --}}
        @if($isExistingProduct)
            <div class="flex items-center gap-2">
                <span class="bg-amber-100 text-amber-800 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider border border-amber-200 animate-pulse">
                    🔄 Mode: Tambah Stok Masuk
                </span>
                <button wire:click="resetForm" class="text-xs text-red-600 hover:underline font-bold">Batal</button>
            </div>
        @else
            <span class="bg-blue-100 text-blue-800 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider border border-blue-200">
                ✨ Mode: Produk Baru
            </span>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-500 text-white rounded-lg font-bold text-sm shadow-md flex items-center gap-2">
            <span>✅</span> {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- 1. Input Nama Produk + Live Search Dropdown --}}
        <div class="md:col-span-2 relative">
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Nama Produk (Model HP)</label>
            <input type="text" 
                   wire:model.live.debounce.300ms="name" 
                   @disabled($isExistingProduct)
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm disabled:bg-gray-100 disabled:text-gray-700 font-medium" 
                   placeholder="Ketik nama HP, contoh: Samsung Galaxy S23">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            {{-- DROPDOWN HASIL PENCARIAN (Hanya muncul jika user sedang mengetik produk baru) --}}
            @if(!empty($name) && !$isExistingProduct && !empty($searchResults))
                <div class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-md shadow-xl z-50 max-h-60 overflow-y-auto divide-y divide-gray-100">
                    <div class="p-2 bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Produk Serupa yang Sudah Terdaftar:</div>
                    @foreach($searchResults as $searchResult)
                        <button type="button" 
                                wire:click="selectProduct({{ $searchResult->id }})"
                                class="w-full text-left px-4 py-2.5 hover:bg-blue-50 text-sm transition-colors flex justify-between items-center group">
                            <div>
                                <span class="font-semibold text-gray-800 group-hover:text-blue-700">{{ $searchResult->name }}</span>
                                <div class="text-[10px] text-gray-400 uppercase">{{ $searchResult->brand }} | SKU: {{ $searchResult->sku }}</div>
                            </div>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-mono">Sisa: {{ $searchResult->stock }}</span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 2. SKU / Kode Unit (Otomatis Terisi / Diisi Manual jika barang baru) --}}
        <div class="md:col-span-1">
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">SKU / Kode Unit</label>
            <input type="text" wire:model="sku" @disabled($isExistingProduct) class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm disabled:bg-gray-100 disabled:text-gray-500" placeholder="Otomatis / Buat Baru">
            @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- 3. Brand --}}
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Brand</label>
            <input type="text" wire:model="brand" @disabled($isExistingProduct) class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm disabled:bg-gray-100 disabled:text-gray-500" placeholder="Samsung">
        </div>

        {{-- 4. Kategori --}}
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Kategori</label>
            <input type="text" wire:model="category" @disabled($isExistingProduct) class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm disabled:bg-gray-100 disabled:text-gray-500" placeholder="Smartphone">
            @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- 5. Satuan --}}
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Satuan</label>
            <select wire:model="unit_id" @disabled($isExistingProduct) class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm disabled:bg-gray-100 disabled:text-gray-500">
                <option value="">-- Pilih Satuan --</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->short_name }})</option>
                @endforeach
            </select>
            @error('unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- 6. Harga Beli --}}
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Harga Beli (Modal)</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">Rp</span>
                <input type="number" wire:model="purchase_price" class="w-full pl-8 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
        </div>

        {{-- 7. Harga Jual --}}
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Harga Jual</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">Rp</span>
                <input type="number" wire:model="selling_price" class="w-full pl-8 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
        </div>

        {{-- 8. Dinamis Kolom Jumlah Stok --}}
        <div class="md:col-span-1">
            @if($isExistingProduct)
                <label class="block text-xs font-black text-amber-700 mb-1 uppercase">Jumlah Stok Masuk (+)</label>
                <input type="number" wire:model="stock_masuk" class="w-full border-amber-300 bg-amber-50 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm font-bold text-amber-900">
                <p class="text-[10px] text-amber-600 mt-1 font-medium">Stok saat ini di rak: {{ $currentStockQty }} Unit</p>
            @else
                <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Stok Awal</label>
                <input type="number" wire:model="stock" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            @endif
        </div>

        {{-- 9. Batas Minimum --}}
        <div class="md:col-span-1">
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Batas Stok Minimum</label>
            <input type="number" wire:model="min_stock" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>

        {{-- 10. Tombol Eksekusi Pintar --}}
        <div class="md:col-span-2 flex items-end">
            @if($isExistingProduct)
                <button wire:click="addExistingStock" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-md shadow-lg transition duration-200 uppercase text-sm tracking-widest">
                    📥 Pasok Stok Masuk
                </button>
            @else
                <button wire:click="store" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-lg transition duration-200 uppercase text-sm tracking-widest">
                    🚀 Simpan Produk Baru
                </button>
            @endif
        </div>
    </div>
</div>