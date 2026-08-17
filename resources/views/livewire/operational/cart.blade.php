    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
        {{-- DAFTAR PRODUK (BAGIAN KIRI) --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-black mb-5 text-slate-800 border-b border-slate-100 pb-3 text-lg tracking-tight flex items-center gap-2">
                📱 Pilih Unit HP
            </h3>
            
            <div class="space-y-3 custom-scrollbar" style="max-height: 600px; overflow-y: auto; padding-right: 4px;">
                @forelse($productsByBrand as $brand => $products)
                    <div x-data="{ open: false }" class="border border-slate-200 rounded-xl overflow-hidden shadow-sm transition-all duration-200">
                        <button @click="open = !open" class="w-full p-4 bg-slate-50 flex justify-between items-center hover:bg-slate-100 transition-colors">
                            <span class="font-black uppercase tracking-widest text-slate-700 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-tag text-slate-400 text-xs"></i> {{ $brand }}
                            </span>
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full font-bold shadow-sm">
                                    {{ count($products) }} Model
                                </span>
                                <svg :class="{'rotate-180': open}" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>
                        
                        <div x-show="open" x-transition.opacity class="p-4 bg-white border-t border-slate-100" style="display: none;">
                            <div class="grid grid-cols-1 gap-3">
                                @foreach($products as $product)
                                    <button wire:click="addToCart({{ $product->id }})" 
                                            class="p-4 border border-slate-200 rounded-xl hover:bg-blue-50 hover:border-blue-400 transition-all text-left group shadow-sm flex flex-col justify-between h-full">
                                        <p class="font-black text-slate-800 group-hover:text-blue-700 text-sm leading-tight mb-3">
                                            {{ $product->name }}
                                        </p>
                                        <div class="flex justify-between items-end mt-auto w-full">
                                            <div class="flex flex-col gap-1">
                                                <p class="text-[10px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 font-bold inline-block border border-slate-200 w-max">
                                                    Stok: {{ $product->stock }} {{ $product->unit->short_name ?? 'Unit' }}
                                                </p>
                                            </div>
                                            <p class="text-blue-600 font-black text-sm">
                                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 italic text-sm font-medium bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        Tidak ada produk tersedia atau stok habis.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- KERANJANG BELANJA (BAGIAN KANAN) --}}
        <div class="bg-slate-900 p-6 rounded-2xl border border-slate-700">
            <h3 class="font-black mb-5 text-white border-b border-slate-700 pb-3 text-lg tracking-tight flex items-center gap-2">
                🛒 Keranjang Penjualan
            </h3>

            <div class="space-y-3">
                @forelse($cart as $id => $item)
                    <div class="flex items-center justify-between bg-slate-800 p-4 rounded-xl border border-slate-700">
                        <div class="flex-1">
                            <p class="font-bold text-white text-sm leading-tight">{{ $item['name'] }}</p>
                            <p class="text-xs text-blue-400 font-bold mt-1">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                        </div>

                        <div class="flex items-center gap-3 ml-3">
                            {{-- TOMBOL PLUS MINUS --}}
                            <div class="flex items-center gap-1">
                                <button wire:click="decrementQty({{ $id }})"
                                    class="w-8 h-8 flex items-center justify-center bg-slate-700 hover:bg-red-600 text-white rounded-lg font-black text-lg transition-colors shadow">
                                    −
                                </button>
                                <span class="w-8 text-center font-black text-white text-sm">{{ $item['qty'] }}</span>
                                <button wire:click="incrementQty({{ $id }})"
                                    class="w-8 h-8 flex items-center justify-center bg-slate-700 hover:bg-green-600 text-white rounded-lg font-black text-lg transition-colors shadow">
                                    +
                                </button>
                            </div>

                            {{-- TOMBOL HAPUS --}}
                            <button wire:click="removeFromCart({{ $id }})" class="text-slate-500 hover:text-red-400 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500 italic text-sm">Keranjang kosong.</div>
                @endforelse
            </div>

            <div class="mt-6 border-t border-slate-700 pt-4">
                <div class="flex justify-between items-center mb-5">
                    <span class="font-bold text-slate-400 uppercase tracking-widest text-xs">Total</span>
                    <span class="text-2xl font-black text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <button wire:click="checkout" @if(empty($cart)) disabled @endif
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3.5 rounded-xl font-black tracking-tight text-base shadow-lg shadow-blue-900/40 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    Selesaikan Transaksi
                </button>
            </div>
        </div>
    </div>