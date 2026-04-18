<?php

namespace App\Livewire\Manager;

use App\Models\Sale;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SalesChart extends Component
{
    public function render()
    {
        $salesData = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total')
        )
        ->groupBy('date')
        ->get();

        return view('livewire.manager.sales-chart', [
            'labels' => $salesData->pluck('date'),
            'values' => $salesData->pluck('total'),
        ]);
    }
}