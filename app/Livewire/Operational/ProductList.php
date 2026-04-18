<?php

namespace App\Livewire\Operational;

use App\Models\Product;
use App\Imports\ProductImport;
use Livewire\Component;
use Livewire\WithFileUploads; // Wajib untuk upload file
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ProductList extends Component
{
    use WithFileUploads;

    public $file_excel;

    public function importExcel()
    {
        // Proteksi Tambahan: Cek role di sisi Server
        if (Auth::user()->role !== 'staff') {
            session()->flash('error', 'Akses ditolak! Hanya Rafi (Staff) yang boleh import data.');
            return;
        }

        $this->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            Excel::import(new ProductImport, $this->file_excel->getRealPath());
            
            $this->reset('file_excel');
            session()->flash('message', 'Data Produk Berhasil Diimport, Rafi!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses file. Pastikan format kolom benar.');
        }
    }

    public function render()
    {
        return view('livewire.operational.product-list', [
            'products' => Product::orderBy('stock', 'asc')->get()
        ]);
    }
}