<?php

namespace App\Livewire\Manager;

use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SalesChart extends Component
{
    public function render()
    {
        // 1. DATA TREND PENJUALAN (Line Chart - Omzet Harian)
        $salesData = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total')
        )
        ->where('created_at', '>=', now()->subDays(30)) // Ambil data 30 hari terakhir agar grafik rapi
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        // 2. DATA KLASIFIKASI BI (Doughnut Chart - Fast/Slow Moving)
        // Kita hitung distribusi produk berdasarkan status yang ada di Model
        $allProducts = Product::all();
        
        $classification = [
            'fast'   => $allProducts->where('sales_status', 'Fast Moving')->count(),
            'medium' => $allProducts->where('sales_status', 'Medium Moving')->count(),
            'slow'   => $allProducts->where('sales_status', 'Slow Moving')->count(),
        ];

        // 3. DATA REKOMENDASI RESTOCK (Tabel Analisis ROP)
        // Ambil produk yang stoknya sudah menyentuh atau di bawah Reorder Point
        $restockRecommendations = $allProducts->filter(function($product) {
            return $product->stock <= $product->reorder_point;
        })->take(5); // Ambil top 5 yang paling kritis saja untuk dashboard

        return view('livewire.manager.sales-chart', [
            // Untuk Grafik Line
            'labels' => $salesData->pluck('date'),
            'values' => $salesData->pluck('total'),
            
            // Untuk Grafik Doughnut
            'classification' => $classification,
            
            // Untuk Tabel Analisis
            'restockList' => $restockRecommendations,
        ]);
    }
}