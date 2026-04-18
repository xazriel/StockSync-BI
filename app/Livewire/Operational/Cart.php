<?php

namespace App\Livewire\Operational;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public $cart = [];
    public $total = 0;

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if ($product->stock <= 0) {
            session()->flash('error', 'Stok Habis!');
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = [
                'name' => $product->name,
                'price' => $product->selling_price,
                'qty' => 1
            ];
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public function checkout()
    {
        // Simpan ke tabel Sales (Header)
        $sale = Sale::create([
            'invoice_number' => 'INV-' . now()->format('YmdHis'),
            'user_id' => Auth::id(),
            'total_price' => $this->total,
            'pay_amount' => $this->total, // Simulasi uang pas
            'change_amount' => 0,
        ]);

        foreach ($this->cart as $id => $item) {
            // Simpan ke detail
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $id,
                'quantity' => $item['qty'],
                'selling_price' => $item['price'],
            ]);

            // Kurangi Stok HP
            Product::find($id)->decrement('stock', $item['qty']);
        }

        $this->cart = [];
        $this->total = 0;
        session()->flash('success', 'Transaksi Berhasil!');
    }

    public function render()
    {
        return view('livewire.operational.cart', [
            'products' => Product::where('stock', '>', 0)->get()
        ]);
    }
}