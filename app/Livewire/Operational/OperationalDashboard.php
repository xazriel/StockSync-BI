<?php

namespace App\Livewire\Operational;

use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Exports\SalesExport;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OperationalDashboard extends Component
{
    use WithFileUploads;

    public $fileImport;

    private function getTopProducts(): array
    {
        $now        = Carbon::now();
        $startOfDay = $now->copy()->startOfDay();
        $endOfDay   = $now->copy()->endOfDay();

        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_qty'))
            ->whereBetween('sales.created_at', [$startOfDay, $endOfDay])
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_qty', 'DESC')
            ->limit(5)
            ->get();

        if ($topProducts->isEmpty()) {
            return ['labels' => [], 'values' => []];
        }

        return [
            'labels' => $topProducts->pluck('name')->toArray(),
            'values' => $topProducts->pluck('total_qty')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    #[On('order-created')]
    public function refreshOperationalData()
    {
        $chart = $this->getTopProducts();

        $this->dispatch('initChart',
            labels: $chart['labels'],
            values: $chart['values']
        );
    }

    public function exportSales()
    {
        return Excel::download(new SalesExport, 'Laporan_Penjualan_' . now()->format('d-m-Y') . '.xlsx');
    }

    public function importStock()
    {
        $this->validate([
            'fileImport' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ProductImport, $this->fileImport->getRealPath());
            $this->fileImport = null;
            session()->flash('message', 'Data stok berhasil diperbarui masal!');

            $chart = $this->getTopProducts();
            $this->dispatch('initChart',
                labels: $chart['labels'],
                values: $chart['values']
            );
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $now        = Carbon::now();
        $startOfDay = $now->copy()->startOfDay();
        $endOfDay   = $now->copy()->endOfDay();

        $todaySalesCount = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])->count();
        $todayRevenue    = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])->sum('total_price');

        $chart = $this->getTopProducts();

        $recentLogs = Sale::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.operational.operational-dashboard', [
            'salesCount' => $todaySalesCount,
            'revenue'    => $todayRevenue,
            'labels'     => $chart['labels'],
            'values'     => $chart['values'],
            'recentLogs' => $recentLogs,
        ]);
    }
}