<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'sku' => 'HP-APL-I15P',
                'name' => 'iPhone 15 Pro 256GB',
                'brand' => 'Apple',
                'purchase_price' => 17500000,
                'selling_price' => 18999000,
                'stock' => 3, // Kritis -> Memicu Rekomendasi Restock Bintang
                'min_stock' => 10,
                'category' => 'Smartphone',
            ],
            [
                'sku' => 'HP-SAM-S24U',
                'name' => 'Samsung Galaxy S24 Ultra',
                'brand' => 'Samsung',
                'purchase_price' => 19000000,
                'selling_price' => 21499000,
                'stock' => 4, // Kritis -> Memicu Rekomendasi Restock Bintang
                'min_stock' => 8,
                'category' => 'Smartphone',
            ],
            [
                'sku' => 'HP-XIA-RN13P',
                'name' => 'Redmi Note 13 Pro+ 5G',
                'brand' => 'Xiaomi',
                'purchase_price' => 5200000,
                'selling_price' => 5999000,
                'stock' => 15, // Aman
                'min_stock' => 5,
                'category' => 'Smartphone',
            ],
            [
                'sku' => 'HP-OPP-R11P',
                'name' => 'Oppo Reno 11 Pro 5G',
                'brand' => 'Oppo',
                'purchase_price' => 7800000,
                'selling_price' => 8999000,
                'stock' => 2, // Sangat Kritis
                'min_stock' => 6,
                'category' => 'Smartphone',
            ],
            [
                'sku' => 'HP-VIV-V30P',
                'name' => 'Vivo V30 Pro',
                'brand' => 'Vivo',
                'purchase_price' => 7500000,
                'selling_price' => 8599000,
                'stock' => 12, // Aman
                'min_stock' => 5,
                'category' => 'Smartphone',
            ],
            [
                'sku' => 'HP-INF-N40P',
                'name' => 'Infinix Note 40 Pro',
                'brand' => 'Infinix',
                'purchase_price' => 3100000,
                'selling_price' => 3499000,
                'stock' => 25, // Stok Melimpah (Slow Moving Test)
                'min_stock' => 10,
                'category' => 'Smartphone',
            ],
        ];

        foreach ($products as $product) {
    \App\Models\Product::updateOrCreate(
        ['sku' => $product['sku']], // Cari berdasarkan SKU
        $product // Jika ketemu update, jika tidak buat baru
    );
}}
}