<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number', 
        'user_id', 
        'total_price', 
        'pay_amount', 
        'change_amount'
    ];

    /**
     * Relasi ke User (Kasir/Staff yang melakukan transaksi)
     * Ini yang tadi bikin Error 500 karena belum ada fungsinya.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Item Penjualan (Penting untuk detail transaksi)
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    
}