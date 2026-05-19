<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $table = 'sale_items'; // ← tambah ini

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price', // ← ganti dari selling_price
    ];
}