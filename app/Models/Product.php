<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'brand',
        'unit_id',
        'purchase_price',
        'selling_price',
        'stock',
        'min_stock',
        'category'
    ];

    /**
     * RELASI
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * ==========================================
     * BUSINESS INTELLIGENCE LOGIC (UNTUK BINTANG)
     * ==========================================
     */

    /**
     * PENDEKATAN 1: Simple Moving Average (SMA) 7 Hari
     * Menghitung rata-rata penjualan per hari dalam 7 hari terakhir.
     */
    public function getMovingAverageAttribute()
    {
        // Mengambil total kuantitas terjual dari table sale_items dalam 7 hari terakhir
        $totalSales = $this->saleItems()
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('quantity');

        return $totalSales / 7;
    }

    /**
     * PENDEKATAN 2: Reorder Point (ROP) & Safety Stock
     * Rumus: (Lead Time * Average Sales) + Safety Stock
     * Kita asumsikan Lead Time supplier adalah 2 hari.
     */
    public function getReorderPointAttribute()
    {
        $leadTime = 2; 
        $safetyStock = $this->min_stock; // Menggunakan min_stock sebagai stok pengaman
        
        // Rumus ROP
        return ($leadTime * $this->moving_average) + $safetyStock;
    }

    /**
     * PENDEKATAN 3: Klasifikasi Fast & Slow Moving
     * Berdasarkan volume penjualan rata-rata harian.
     */
    public function getSalesStatusAttribute()
    {
        $avg = $this->moving_average;

        if ($avg >= 1.5) {
            return 'Fast Moving'; // Sangat laku
        } elseif ($avg >= 0.5) {
            return 'Medium Moving'; // Laku standar
        } else {
            return 'Slow Moving'; // Kurang laku
        }
    }

    /**
     * FITUR TAMBAHAN: Rekomendasi Jumlah Restock
     * Memberikan saran berapa banyak yang harus diorder ke supplier
     */
    public function getRestockRecommendationAttribute()
    {
        if ($this->stock <= $this->reorder_point) {
            // Saran: Penuhi kebutuhan untuk 10 hari kedepan
            $needed = ceil(($this->moving_average * 10) - $this->stock);
            return $needed > 0 ? $needed : 0;
        }
        return 0;
    }
}   