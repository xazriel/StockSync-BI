<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number', 'user_id', 'total_price', 'pay_amount', 'change_amount'
    ];
}