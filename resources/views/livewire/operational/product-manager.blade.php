<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    <div class="flex items-center gap-2 mb-6">
        <div class="p-2 bg-blue-600 rounded-lg text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 uppercase tracking-tight">Pendaftaran Produk Baru (Rafi)</h3>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 font-medium animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- SKU & Nama --}}
        <div class="md:col-span-1">
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">SKU / Kode Unit</label>
            <input type="text" wire:model="sku" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Contoh: SAM-BB-01">
            @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Nama Produk (Model HP)</label>
            <input type="text" wire:model="name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Contoh: Samsung Galaxy BigBang">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Brand</label>
            <input type="text" wire:model="brand" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Samsung">
        </div>

        {{-- Kategori & Unit --}}
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Kategori</label>
            <input type="text" wire:model="category" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Smartphone">
            @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Satuan</label>
            <select wire:model="unit_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">-- Pilih Satuan --</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->short_name }})</option>
                @endforeach
            </select>
            @error('unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Harga --}}
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Harga Beli (Modal)</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs text-sm">Rp</span>
                <input type="number" wire:model="purchase_price" class="w-full pl-8 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Harga Jual</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs text-sm">Rp</span>
                <input type="number" wire:model="selling_price" class="w-full pl-8 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
        </div>

        {{-- Stok --}}
        <div class="md:col-span-1">
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Stok Awal</label>
            <input type="number" wire:model="stock" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
        <div class="md:col-span-1">
            <label class="block text-xs font-black text-gray-500 mb-1 uppercase">Batas Stok Minimum</label>
            <input type="number" wire:model="min_stock" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>

        <div class="md:col-span-2 flex items-end">
            <button wire:click="store" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-lg transition duration-200 uppercase text-sm tracking-widest">
                🚀 Simpan Produk Baru
            </button>
        </div>
    </div>
</div>