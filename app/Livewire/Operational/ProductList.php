<?php

namespace App\Livewire\Operational;

use App\Models\Product;
use App\Imports\ProductImport;
use Livewire\Component;
use Livewire\WithFileUploads; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ProductList extends Component
{
    use WithFileUploads;

    public $file_excel;

    /**
     * Listener untuk sinkronisasi antar komponen Livewire.
     * productUpdated: refresh tabel saat ada produk baru/edit.
     */
    protected $listeners = ['productUpdated' => '$refresh'];

    /**
     * Fungsi untuk import data produk via Excel (Khusus Rafi/Staff).
     */
    public function importExcel()
    {
        if (Auth::user()->role !== 'staff') {
            session()->flash('error', 'Akses ditolak! Hanya Rafi (Staff) yang boleh import data.');
            return;
        }

        $this->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        try {
            Excel::import(new ProductImport, $this->file_excel->getRealPath());
            
            $this->reset('file_excel');
            $this->dispatch('productUpdated'); // Refresh tabel setelah import
            session()->flash('message', 'Data Produk Berhasil Diimport, Rafi!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses file. Pastikan format kolom benar.');
        }
    }

    /**
     * Fungsi untuk memicu mode Edit.
     * Mengirimkan ID produk ke komponen ProductManager.
     */
    public function editProduct($id)
    {
        // Mengirimkan event ke komponen ProductManager.php
        $this->dispatch('editProduct', id: $id);
    }

    /**
     * Fungsi untuk menghapus produk dari database.
     */
    public function deleteProduct($id)
    {
        // Proteksi: Hanya staff yang boleh hapus
        if (Auth::user()->role !== 'staff') {
            session()->flash('error', 'Akses ditolak!');
            return;
        }

        $product = Product::find($id);
        if ($product) {
            $product->delete();
            session()->flash('message', 'Produk ' . $product->name . ' berhasil dihapus!');
            $this->dispatch('productUpdated');
        }
    }

    public function render()
    {
        return view('livewire.operational.product-list', [
            /**
             * Menggunakan Eager Loading (with unit) agar query lebih ringan
             * Diurutkan berdasarkan stok terkecil (agar produk yang mau habis muncul duluan)
             */
            'products' => Product::with('unit')->orderBy('stock', 'asc')->get()
        ]);
    }
}