<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Transaction::whereIn('status', ['paid', 'success', 'shipped', 'completed'])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Kode Transaksi',
            'Nama Pembeli',
            'Total Transaksi',
            'Admin Fee',
            'Nilai Produk',
            'Ongkir',
            'Status',
            'Tanggal',
        ];
    }

    public function map($trx): array
    {
        return [
            $trx->id,
            $trx->invoice_number,
            $trx->user->name ?? 'User Terhapus',
            'Rp ' . number_format($trx->total_price, 0, ',', '.'),
            'Rp ' . number_format($trx->admin_fee, 0, ',', '.'),
            'Rp ' . number_format($trx->total_price - $trx->admin_fee - $trx->shipping_fee, 0, ',', '.'),
            'Rp ' . number_format($trx->shipping_fee, 0, ',', '.'),
            strtoupper($trx->status),
            $trx->created_at->format('d/m/Y H:i'),
        ];
    }
}
