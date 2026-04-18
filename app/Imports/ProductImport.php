<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return Product::updateOrCreate(
            ['sku' => $row['sku']], // Gunakan SKU sebagai kunci unik (sesuai migration)
            [
                'name'           => $row['name'],
                'brand'          => $row['brand'] ?? '-',
                'purchase_price' => $row['purchase_price'] ?? 0,
                'selling_price'  => $row['selling_price'] ?? 0,
                'stock'          => $row['stock'] ?? 0,
                'min_stock'      => $row['min_stock'] ?? 5,
                'category'       => $row['category'] ?? 'Umum',
            ]
        );
    }
}