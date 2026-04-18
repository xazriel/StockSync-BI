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
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique(); // Contoh: INV-20231027-001
        $table->foreignId('user_id')->constrained(); // Siapa staff yang melayani (Rafi)
        $table->decimal('total_price', 15, 2);
        $table->decimal('pay_amount', 15, 2); // Uang yang dibayar pembeli
        $table->decimal('change_amount', 15, 2); // Kembalian
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
