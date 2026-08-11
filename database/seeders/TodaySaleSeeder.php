<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TodaySaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $user = User::first();

        if (!$user) {
            $this->command->error('Gagal: Tidak ada User di database.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->error('Gagal: Tabel produk masih kosong.');
            return;
        }

        $this->command->info('Memulai seeding data transaksi KHUSUS HARI INI...');

        // Random: Anggap saja ada 5 sampai 10 transaksi khusus hari ini
        $transactionCount = rand(5, 10);

        for ($j = 0; $j < $transactionCount; $j++) {
            // Waktu acak hari ini antara jam 08:00 sampai waktu sekarang
            $now = Carbon::now();
            $startOfDay = $now->copy()->startOfDay()->addHours(8); // Buka toko jam 8 pagi
            
            // Jika waktu sekarang masih sebelum jam 8 pagi, set minimal jam sekarang - 1 jam
            if ($now->lessThan($startOfDay)) {
                $startOfDay = $now->copy()->subHours(1);
            }
            
            $randomTimestamp = rand($startOfDay->timestamp, $now->timestamp);
            $date = Carbon::createFromTimestamp($randomTimestamp);
            
            $invoiceNumber = 'INV-' . $date->format('YmdHis');
            
            // Pilih 1-3 produk secara acak untuk transaksi ini
            $itemsToBuy = $products->random(rand(1, 3));
            $totalPrice = 0;
            $tempItems = [];

            foreach ($itemsToBuy as $product) {
                $qty = rand(1, 3);
                $subtotal = $qty * $product->selling_price;
                $totalPrice += $subtotal;
                
                $tempItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->selling_price,
                ];
            }

            // Simulasi pembayaran
            $payAmount = ceil($totalPrice / 50000) * 50000; 
            if ($payAmount < $totalPrice) $payAmount = ceil($totalPrice / 100000) * 100000; 
            if ($payAmount < $totalPrice) $payAmount = $totalPrice;
            $changeAmount = $payAmount - $totalPrice;

            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'pay_amount' => $payAmount,
                'change_amount' => $changeAmount,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

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

        $this->command->info("Berhasil menambahkan {$transactionCount} transaksi untuk hari ini dengan waktu acak!");
    }
}
