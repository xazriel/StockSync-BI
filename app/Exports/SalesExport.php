<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Ambil data sales terbaru
    */
    public function collection()
    {
        return Sale::with('user')->orderBy('created_at', 'desc')->get();
    }

    /**
    * Header kolom di Excel-nya nanti
    */
    public function headings(): array
    {
        return [
            'Nomor Invoice',
            'Kasir',
            'Total Belanja',
            'Jumlah Bayar',
            'Kembalian',
            'Tanggal Transaksi',
        ];
    }

    /**
    * Memetakan kolom database ke kolom Excel
    */
    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->user->name, // Mengambil nama 'Rafi' dari relasi user
            'Rp ' . number_format($sale->total_price, 0, ',', '.'),
            'Rp ' . number_format($sale->pay_amount, 0, ',', '.'),
            'Rp ' . number_format($sale->change_amount, 0, ',', '.'),
            $sale->created_at->format('d-m-Y H:i'),
        ];
    }
}