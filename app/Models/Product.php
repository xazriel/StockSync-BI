<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'sku', // Harus ada di sini
    'name', 
    'brand', 
    'purchase_price', 
    'selling_price', 
    'stock', 
    'min_stock', 
    'category'
];

// Hitung berapa kali produk ini terjual (Fast/Slow Moving)
public function getSalesStatusAttribute()
{
    $salesCount = \App\Models\SaleItem::where('product_id', $this->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->sum('quantity');

    if ($salesCount > 10) return 'Fast Moving';
    if ($salesCount > 0) return 'Medium Moving';
    return 'Slow Moving';
}

// Rekomendasi jumlah yang harus di-restock (BI Recommendation)
public function getRestockRecommendationAttribute()
{
    if ($this->stock <= $this->min_stock) {
        // Logika BI sederhana: Sarankan beli 2x lipat dari stok minimum
        return $this->min_stock * 2; 
    }
    return 0;
}

public function unit()
{
    return $this->belongsTo(Unit::class);
}

}