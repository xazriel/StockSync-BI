<?php

namespace App\Livewire\Manager;

use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class SalesChart extends Component
{
    // Deklarasikan properti agar bisa dilempar secara reaktif ke Javascript
    public $labels = [];
    public $values = [];
    public $classification = [];

    public function mount()
    {
        // Jalankan pengisian data awal saat komponen pertama kali dimuat
        $this->loadBusinessIntelligenceData();
    }

    // LISTENER UTAMA: Menguping sinyal 'order-created' dari komponen Cart Rafi
    #[On('order-created')]
    public function refreshChartData()
    {
        // Ambil data terbaru dari Database
        $this->loadBusinessIntelligenceData();

        // Kirimkan event ke Javascript Blade untuk menggambar ulang grafik Chart.js secara instan
        $this->dispatch('bi-data-updated', [
            'labels' => $this->labels,
            'values' => $this->values,
            'classification' => $this->classification,
        ]);
    }

    /**
     * Kumpulan Rumus & Query Pemrosesan Data Business Intelligence (BI Engine)
     */
    private function loadBusinessIntelligenceData()
    {
        // 1. DATA TREND PENJUALAN (Line Chart - Omzet Harian)
        $salesData = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        $this->labels = $salesData->pluck('date')->toArray();
        $this->values = $salesData->pluck('total')->toArray();

        // 2. DATA KLASIFIKASI BI (Doughnut Chart - Fast/Slow Moving)
        $allProducts = Product::all();
        
        $this->classification = [
            'fast'   => $allProducts->where('sales_status', 'Fast Moving')->count(),
            'medium' => $allProducts->where('sales_status', 'Medium Moving')->count(),
            'slow'   => $allProducts->where('sales_status', 'Slow Moving')->count(),
        ];
    }

    public function render()
    {
        // 3. DATA REKOMENDASI RESTOCK (Tabel Analisis ROP)
        // Tetap ditaruh di render agar query tabel otomatis refresh saat state komponen berubah
        $restockRecommendations = Product::all()->filter(function($product) {
            return $product->stock <= $product->reorder_point;
        })->take(5);

        return view('livewire.manager.sales-chart', [
            'restockList' => $restockRecommendations,
        ]);
    }
}