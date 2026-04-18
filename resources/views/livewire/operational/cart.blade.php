<div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
    {{-- DAFTAR PRODUK --}}
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-bold mb-4">Pilih HP</h3>
        <div class="grid grid-cols-2 gap-4">
            @foreach($products as $product)
                <button wire:click="addToCart({{ $product->id }})" class="p-3 border rounded hover:bg-blue-50 text-left">
                    <p class="font-semibold">{{ $product->name }}</p>
                    <p class="text-sm text-gray-500">Stok: {{ $product->stock }}</p>
                    <p class="text-blue-600 font-bold">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                </button>
            @endforeach
        </div>
    </div>

    {{-- KERANJANG BELANJA --}}
    <div class="bg-gray-100 p-4 rounded shadow">
        <h3 class="font-bold mb-4">Keranjang Penjualan</h3>
        @foreach($cart as $item)
            <div class="flex justify-between mb-2 border-b pb-1">
                <span>{{ $item['name'] }} (x{{ $item['qty'] }})</span>
                <span>Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
            </div>
        @endforeach
        
        <div class="mt-4 border-t pt-4">
            <h4 class="text-xl font-bold">Total: Rp {{ number_format($total, 0, ',', '.') }}</h4>
            <button wire:click="checkout" class="w-full mt-4 bg-green-600 text-white py-2 rounded font-bold hover:bg-green-700">
                Selesaikan Transaksi
            </button>
        </div>
        
        @if (session()->has('success'))
            <div class="mt-4 p-2 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif
    </div>
</div>