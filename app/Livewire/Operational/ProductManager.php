<?php

namespace App\Livewire\Operational;

use App\Models\Product;
use App\Models\Unit;
use Livewire\Component;

class ProductManager extends Component
{
    // Properties untuk form
    public $sku, $name, $brand, $purchase_price, $selling_price, $stock, $min_stock = 5, $category, $unit_id;
    
    // Properties tambahan untuk fungsi Edit
    public $isEditMode = false;
    public $productId;

    // Mendengarkan event 'editProduct' dari ProductList
    protected $listeners = ['editProduct' => 'loadProductData'];

    public function render()
    {
        return view('livewire.operational.product-manager', [
            'units' => Unit::all() 
        ]);
    }

    /**
     * Mengisi form dengan data produk yang dipilih untuk diedit
     */
    public function loadProductData($id)
    {
        $product = Product::findOrFail($id);
        
        $this->productId = $id;
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->brand = $product->brand;
        $this->unit_id = $product->unit_id;
        $this->purchase_price = $product->purchase_price;
        $this->selling_price = $product->selling_price;
        $this->stock = $product->stock;
        $this->min_stock = $product->min_stock;
        $this->category = $product->category;
        
        $this->isEditMode = true;
        
        // Membersihkan pesan error lama jika ada
        $this->resetErrorBag();
    }

    public function store()
    {
        // VALIDASI: SKU unik kecuali untuk produk yang sedang diedit
        $this->validate([
            'sku'            => 'required|unique:products,sku,' . ($this->productId ?? 'NULL'),
            'name'           => 'required|min:3',
            'brand'          => 'nullable',
            'unit_id'        => 'required|exists:units,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'min_stock'      => 'required|integer|min:1',
            'category'       => 'required',
        ]);

        if ($this->isEditMode) {
            // LOGIC UPDATE
            $product = Product::find($this->productId);
            $product->update([
                'sku'            => $this->sku,
                'name'           => $this->name,
                'brand'          => $this->brand,
                'unit_id'        => $this->unit_id,
                'purchase_price' => $this->purchase_price,
                'selling_price'  => $this->selling_price,
                'stock'          => $this->stock,
                'min_stock'      => $this->min_stock,
                'category'       => $this->category,
            ]);
            session()->flash('message', 'Data Produk ' . $this->name . ' Berhasil Diperbarui!');
        } else {
            // LOGIC CREATE (BAWAAN SEBELUMNYA)
            Product::create([
                'sku'            => $this->sku,
                'name'           => $this->name,
                'brand'          => $this->brand,
                'unit_id'        => $this->unit_id,
                'purchase_price' => $this->purchase_price,
                'selling_price'  => $this->selling_price,
                'stock'          => $this->stock,
                'min_stock'      => $this->min_stock,
                'category'       => $this->category,
            ]);
            session()->flash('message', 'Unit ' . $this->name . ' Berhasil Terdaftar!');
        }

        $this->cancelEdit(); // Reset form dan matikan mode edit
        $this->dispatch('productUpdated'); // Refresh tabel
    }

    /**
     * Membatalkan mode edit dan mereset form
     */
    public function cancelEdit()
    {
        $this->isEditMode = false;
        $this->productId = null;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->reset(['sku', 'name', 'brand', 'purchase_price', 'selling_price', 'stock', 'category', 'unit_id']);
        $this->min_stock = 5; 
    }
}