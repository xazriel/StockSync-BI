<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->string('description'); // Contoh: Listrik, Gaji, Sewa Ruko 
        $table->decimal('amount', 15, 2); // Nominal pengeluaran
        $table->date('date'); // Tanggal pengeluaran untuk tracking harian 
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
