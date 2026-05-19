<?php

namespace App\Livewire\Operational;

use Livewire\Component;
use App\Models\Product;
use App\Models\Unit;

class ProductManager extends Component
{
    public $sku, $name, $brand, $category, $unit_id, $purchase_price, $selling_price, $stock, $min_stock;

    // Properti Pintar untuk Pencarian & Sinkronisasi
    public $isExistingProduct = false;
    public $stock_masuk = 0;
    public $currentStockQty = 0;
    public $searchResults = []; // Menyimpan list drop-down

    /**
     * HOOK LIVEWIRE: Otomatis mencari produk saat nama diketik
     */
    public function updatedName($value)
    {
        if ($this->isExistingProduct) return;

        if (strlen($value) < 2) {
            $this->searchResults = [];
            return;
        }

        // Cari berdasarkan nama atau brand produk di database
        $this->searchResults = Product::where('name', 'like', '%' . $value . '%')
            ->orWhere('brand', 'like', '%' . $value . '%')
            ->take(5) // Batasi maksimal 5 rekomendasi agar performa cepat
            ->get();
    }

    /**
     * FUNGSI: Dipanggil saat user mengklik salah satu produk di dropdown hasil pencarian
     */
    public function selectProduct($id)
    {
        $product = Product::find($id);

        if ($product) {
            $this->isExistingProduct = true;
            $this->name = $product->name;
            $this->sku = $product->sku; // SKU disimpan di latar belakang
            $this->brand = $product->brand;
            $this->category = $product->category;
            $this->unit_id = $product->unit_id;
            $this->purchase_price = $product->purchase_price;
            $this->selling_price = $product->selling_price;
            $this->min_stock = $product->min_stock;
            $this->currentStockQty = $product->stock;
            $this->stock_masuk = 1;

            // Bersihkan hasil dropdown setelah dipilih
            $this->searchResults = [];
        }
    }

    /**
     * FUNGSI: Simpan tambahan stok untuk produk yang dipilih
     */
    public function addExistingStock()
    {
        $this->validate([
            'sku' => 'required',
            'stock_masuk' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
        ]);

        $product = Product::where('sku', $this->sku)->first();
        
        if ($product) {
            $product->update([
                'stock' => $product->stock + $this->stock_masuk,
                'purchase_price' => $this->purchase_price,
                'selling_price' => $this->selling_price,
                'min_stock' => $this->min_stock
            ]);

            session()->flash('message', "Stok unit {$product->name} berhasil ditambah sebanyak {$this->stock_masuk} unit!");
            
            $this->dispatch('order-created'); // Refresh data tabel & chart dashboard
            $this->resetForm();
        }
    }

    /**
     * FUNGSI: Pendaftaran Produk Baru jika tidak memilih dari dropdown
     */
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'sku' => 'required|unique:products,sku',
            'unit_id' => 'required',
            'stock' => 'required|numeric',
        ]);

        Product::create([
            'sku' => $this->sku,
            'name' => $this->name,
            'brand' => $this->brand,
            'category' => $this->category,
            'unit_id' => $this->unit_id,
            'purchase_price' => $this->purchase_price ?? 0,
            'selling_price' => $this->selling_price ?? 0,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock ?? 0,
        ]);

        session()->flash('message', 'Produk model baru sukses didaftarkan ke sistem!');
        $this->dispatch('order-created');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['sku', 'name', 'brand', 'category', 'unit_id', 'purchase_price', 'selling_price', 'stock', 'stock_masuk', 'min_stock', 'isExistingProduct', 'currentStockQty', 'searchResults']);
    }

    public function render()
    {
        return view('livewire.operational.product-manager', [
            'units' => Unit::all()
        ]);
    }
}