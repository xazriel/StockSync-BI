<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    /**
     * Kolom yang bisa diisi secara massal.
     * Sesuai dengan migration yang kita buat tadi.
     */
    protected $fillable = [
        'description',
        'amount',
        'date',
    ];
}