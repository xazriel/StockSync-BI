<div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
    {{-- DAFTAR PRODUK (BAGIAN KIRI) --}}
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-bold mb-4 text-gray-700 border-b pb-2 text-lg">📱 Pilih Unit HP</h3>
        <div class="grid grid-cols-2 gap-4">
            @foreach($products as $product)
                {{-- Di sini pakainya $product->id, bukan $id --}}
                <button wire:click="addToCart({{ $product->id }})" 
                        class="p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-400 transition-all text-left group shadow-sm">
                    <p class="font-bold text-gray-800 group-hover:text-blue-700">{{ $product->name }}</p>
                    <div class="flex justify-between items-center mt-2">
<p class="text-[10px] px-2 py-0.5 rounded bg-gray-100 text-gray-600">
    Stok: {{ $product->stock }} {{ $product->unit->short_name }}
</p>                        <p class="text-blue-600 font-bold text-sm">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    {{-- KERANJANG BELANJA (BAGIAN KANAN) --}}
    <div class="bg-gray-50 p-4 rounded shadow border border-gray-200">
        <h3 class="font-bold mb-4 text-gray-700 border-b pb-2 text-lg">🛒 Keranjang Penjualan</h3>
        
        <div class="space-y-3">
            @forelse($cart as $id => $item)
                <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">{{ $item['name'] }}</p>
                        <p class="text-xs text-blue-600 font-bold">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        {{-- TOMBOL PLUS MINUS --}}
                        <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                            <button wire:click="decrementQty({{ $id }})" class="w-7 h-7 flex items-center justify-center bg-white rounded shadow-sm hover:bg-red-50 hover:text-red-600 transition-colors font-bold">-</button>
                            <span class="w-8 text-center font-bold text-gray-700 text-sm">{{ $item['qty'] }}</span>
                            <button wire:click="incrementQty({{ $id }})" class="w-7 h-7 flex items-center justify-center bg-white rounded shadow-sm hover:bg-green-50 hover:text-green-600 transition-colors font-bold">+</button>
                        </div>

                        {{-- TOMBOL HAPUS (DI SINI TEMPATNYA) --}}
                        <button wire:click="removeFromCart({{ $id }})" class="text-red-400 hover:text-red-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 italic text-sm">Keranjang kosong.</div>
            @endforelse
        </div>
        
        <div class="mt-6 border-t pt-4">
            <div class="flex justify-between items-center mb-4 text-gray-900">
                <span class="font-medium">Total:</span>
                <span class="text-2xl font-black">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            
            <button wire:click="checkout" @if(empty($cart)) disabled @endif class="w-full bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 disabled:opacity-50">
                Selesaikan Transaksi
            </button>
        </div>
    </div>
</div>