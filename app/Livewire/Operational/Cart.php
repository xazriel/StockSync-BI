<?php

namespace App\Livewire\Operational;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cart extends Component
{
    public $cart = [];
    public $total = 0;

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'Stok Habis!');
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] < $product->stock) {
                $this->cart[$productId]['qty']++;
            } else {
                session()->flash('error', 'Stok tidak mencukupi!');
            }
        } else {
            $this->cart[$productId] = [
                'name'  => $product->name,
                'price' => $product->selling_price,
                'qty'   => 1,
            ];
        }

        $this->calculateTotal();
    }

    public function incrementQty($productId)
    {
        $product = Product::find($productId);

        if ($this->cart[$productId]['qty'] < $product->stock) {
            $this->cart[$productId]['qty']++;
            $this->calculateTotal();
        } else {
            session()->flash('error', 'Maksimal stok tercapai!');
        }
    }

    public function decrementQty($productId)
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] > 1) {
                $this->cart[$productId]['qty']--;
            } else {
                unset($this->cart[$productId]);
            }
            $this->calculateTotal();
        }
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->total = collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang kosong!');
            return;
        }

        DB::transaction(function () {
            $sale = Sale::create([
                'invoice_number' => 'INV-' . now()->format('YmdHis'),
                'user_id'        => Auth::id(),
                'total_price'    => $this->total,
                'pay_amount'     => $this->total,
                'change_amount'  => 0,
            ]);

            foreach ($this->cart as $productId => $item) {
                // Simpan detail item ke sale_items
                SaleDetail::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $productId,
                    'quantity'   => $item['qty'],
                    'price'      => $item['price'],
                ]);

                // Kurangi stok produk
                Product::find($productId)->decrement('stock', $item['qty']);
            }
        });

        $this->cart  = [];
        $this->total = 0;

        session()->flash('success', 'Transaksi Berhasil!');
        $this->dispatch('productUpdated');
        $this->dispatch('order-created');
    }

    public function render()
    {
        return view('livewire.operational.cart', [
            'productsByBrand' => Product::where('stock', '>', 0)->get()->groupBy(function($item) {
                return strtoupper($item->brand);
            }),
        ]);
    }
}