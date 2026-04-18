<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil data pendukung
        $products = Product::all();
        $user = User::first(); // Mengambil user pertama sebagai kasir

        // Validasi awal agar tidak error saat running
        if (!$user) {
            $this->command->error('Gagal: Tidak ada User di database. Silahkan register atau buat UserSeeder dulu!');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->error('Gagal: Tabel produk masih kosong. Jalankan ProductSeeder dulu!');
            return;
        }

        $this->command->info('Memulai seeding data transaksi untuk 7 hari terakhir...');

        // 2. Loop untuk mensimulasikan transaksi 7 hari ke belakang
        for ($i = 7; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Random: Anggap saja ada 3 sampai 6 transaksi per hari
            $transactionCount = rand(3, 6);

            for ($j = 0; $j < $transactionCount; $j++) {
                // Generate Invoice Unik (Penting untuk Rafi)
                $invoiceNumber = 'INV-' . $date->format('Ymd') . '-' . strtoupper(Str::random(5));
                
                // Pilih 1-2 produk secara acak untuk transaksi ini
                $itemsToBuy = $products->random(rand(1, 2));
                $totalPrice = 0;
                $tempItems = [];

                // Hitung total harga sebelum simpan ke tabel sales
                foreach ($itemsToBuy as $product) {
                    $qty = rand(1, 2);
                    $subtotal = $qty * $product->selling_price;
                    $totalPrice += $subtotal;
                    
                    $tempItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $product->selling_price,
                    ];
                }

                // Logika Pembayaran (Penting untuk Rafi agar tidak error default value)
                // Simulasi: Bayar pakai uang bulat (misal total 5.8jt, bayar 6jt)
                $payAmount = ceil($totalPrice / 100000) * 100000; 
                if ($payAmount < $totalPrice) $payAmount = $totalPrice; // Pastikan tidak kurang
                $changeAmount = $payAmount - $totalPrice;

                // 3. Simpan ke tabel Sales (Data Induk)
                $sale = Sale::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => $user->id,
                    'total_price' => $totalPrice,
                    'pay_amount' => $payAmount,
                    'change_amount' => $changeAmount,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // 4. Simpan ke tabel SaleItems (Detail Barang yang Dibeli)
                foreach ($tempItems as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }
            }
        }

        $this->command->info('SaleSeeder Berhasil! Dashboard Bintang & Rafi sekarang sudah penuh data.');
    }
}