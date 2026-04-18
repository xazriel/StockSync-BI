<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Digunakan oleh Rafi saat checkout di kasir.
     */
    protected $fillable = [
        'sale_id', 
        'product_id', 
        'quantity', 
        'price'
    ];

    /**
     * Relasi ke Tabel Sale (Parent Transaksi)
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Relasi ke Tabel Product
     * Sangat penting untuk Bintang (BI) agar bisa tahu
     * produk apa yang paling laku (Fast Moving).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}