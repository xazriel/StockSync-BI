<?php

namespace App\Livewire\Operational;

use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Exports\SalesExport;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OperationalDashboard extends Component
{
    use WithFileUploads;

    public $fileImport;

    /**
     * Fungsi untuk Export Laporan Penjualan ke Excel
     */
    public function exportSales()
    {
        return Excel::download(new SalesExport, 'Laporan_Penjualan_'.now()->format('d-m-Y').'.xlsx');
    }

    /**
     * Fungsi untuk Import Stok Masal
     */
    public function importStock()
    {
        $this->validate([
            'fileImport' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ProductImport, $this->fileImport->getRealPath());
            $this->fileImport = null; 
            session()->flash('message', 'Data stok berhasil diperbarui masal!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Set Timezone eksplisit agar aman
        $now = Carbon::now('Asia/Jakarta');
        $startOfDay = $now->copy()->startOfDay();
        $endOfDay = $now->copy()->endOfDay();

        // 1. Statistik Utama (Monitoring Real-time hari ini)
        $todaySalesCount = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])->count();
        $todayRevenue = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])->sum('total_price');
        
        // 2. Data Grafik: Top 5 Produk Terlaris
        $query = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_qty'))
            ->groupBy('products.name', 'products.id')
            ->orderBy('total_qty', 'DESC')
            ->limit(5);

        // LOGIKA DINAMIS:
        // Jika hari ini ada penjualan, tampilkan data hari ini.
        // Jika hari ini 0, tampilkan tren 7 hari terakhir supaya grafik tidak kosong.
        if ($todaySalesCount > 0) {
            $topProducts = $query->whereBetween('sale_items.created_at', [$startOfDay, $endOfDay])->get();
        } else {
            $topProducts = $query->where('sale_items.created_at', '>=', now()->subDays(7))->get();
        }

        // 3. Log Aktivitas Terbaru (Audit Trail)
        // Menggunakan with('user') untuk relasi kasir
        $recentLogs = Sale::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.operational.operational-dashboard', [
            'salesCount' => $todaySalesCount,
            'revenue'    => $todayRevenue,
            'labels'     => $topProducts->pluck('name'),
            'values'     => $topProducts->pluck('total_qty'),
            'recentLogs' => $recentLogs
        ]);
    }
}