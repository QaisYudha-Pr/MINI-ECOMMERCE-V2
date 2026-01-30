<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\ItemShop;
use App\Models\Courier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Data pendukung
        $buyer = User::role('user')->first();
        $sellers = User::role('seller')->get();
        $adminSeller = User::where('email', 'admin@gmail.com')->first();
        $otherSeller = User::where('email', 'seller@gmail.com')->first();
        
        // Ambil produk secara dinamis
        $allProducts = ItemShop::all();
        if ($allProducts->isEmpty()) return;

        // Pastikan ambil Courier Agency yang benar
        $jekpasMotor = Courier::where('name', 'Jek-Pas')->where('service_name', 'Motor')->first();
        $jekpasMobil = Courier::where('name', 'Jek-Pas')->where('service_name', 'Mobil')->first();
        $kurirLokal = Courier::where('name', 'Kurir Mojokerto')->first();
        
        // Driver-driver
        $driver1 = User::where('email', 'driver1@gmail.com')->first(); // Jekpas Motor
        $driver2 = User::where('email', 'driver2@gmail.com')->first(); // Jekpas Mobil
        $driver3 = User::where('email', 'driver3@gmail.com')->first(); // Kurir Lokal

        if (!$buyer || !$adminSeller || !$otherSeller) return;

        // --- DATA DUMMY 1: Transaksi Tunggal (Success) ---
        $item1 = $allProducts->where('user_id', $adminSeller->id)->random() ?? $allProducts->random();
        Transaction::create([
            'user_id' => $buyer->id,
            'seller_id' => $adminSeller->id,
            'invoice_number' => 'INV-20260130-SINGLE-1',
            'parent_invoice' => 'INV-20260130-SINGLE-1',
            'total_price' => ($item1->harga * 2) + 10000 + 2500,
            'shipping_fee' => 10000,
            'admin_fee' => 2500,
            'status' => 'success',
            'payment_method' => 'midtrans',
            'alamat' => 'Jl. Pahlawan No. 1, Mojokerto',
            'courier_name' => 'Jek-Pas',
            'courier_service' => 'Motor',
            'courier_service_id' => $jekpasMotor->id ?? null,
            'items_details' => [
                [
                    'id' => $item1->id, 
                    'nama_barang' => $item1->nama_barang, 
                    'harga' => $item1->harga, 
                    'quantity' => 2, 
                    'total' => $item1->harga * 2, 
                    'weight' => $item1->berat,
                    'kategori' => $item1->kategori,
                    'gambar' => $item1->gambar,
                    'seller_id' => $adminSeller->id,
                    'seller_name' => $adminSeller->nama_toko
                ]
            ],
            'created_at' => now()->subDay(),
        ]);

        // --- DATA DUMMY 2 & 3: Transaksi Split (Satu Checkout Beda Seller) ---
        $parent2 = 'INV-20260130-SPLIT-2';
        $item2 = $allProducts->where('user_id', $adminSeller->id)->random() ?? $allProducts->random();
        $item3 = $allProducts->where('user_id', $otherSeller->id)->random() ?? $allProducts->random();

        // Seller A (Admin)
        Transaction::create([
            'user_id' => $buyer->id,
            'seller_id' => $adminSeller->id,
            'invoice_number' => $parent2 . '-' . $adminSeller->id,
            'parent_invoice' => $parent2,
            'total_price' => ($item2->harga * 2) + 5000 + 1250,
            'shipping_fee' => 5000,
            'admin_fee' => 1250,
            'status' => 'success',
            'payment_method' => 'midtrans',
            'alamat' => 'Jl. Pahlawan No. 1, Mojokerto',
            'courier_id' => $driver1->id ?? null, 
            'courier_service_id' => $jekpasMotor->id ?? null,
            'items_details' => [[
                'id' => $item2->id,
                'nama_barang' => $item2->nama_barang, 
                'harga' => $item2->harga, 
                'quantity' => 2, 
                'total' => $item2->harga * 2,
                'weight' => $item2->berat,
                'kategori' => $item2->kategori,
                'gambar' => $item2->gambar,
                'seller_id' => $adminSeller->id,
                'seller_name' => $adminSeller->nama_toko
            ]],
            'created_at' => now()->subHours(5),
        ]);
        // Seller B (Penjual Jauh)
        Transaction::create([
            'user_id' => $buyer->id,
            'seller_id' => $otherSeller->id,
            'invoice_number' => $parent2 . '-' . $otherSeller->id,
            'parent_invoice' => $parent2,
            'total_price' => ($item3->harga * 1) + 15000 + 1250,
            'shipping_fee' => 15000,
            'admin_fee' => 1250,
            'status' => 'success',
            'payment_method' => 'midtrans',
            'alamat' => 'Jl. Pahlawan No. 1, Mojokerto',
            'courier_service_id' => $jekpasMotor->id ?? null,
            'items_details' => [[
                'id' => $item3->id,
                'nama_barang' => $item3->nama_barang, 
                'harga' => $item3->harga, 
                'quantity' => 1, 
                'total' => $item3->harga,
                'weight' => $item3->berat,                'kategori' => $item3->kategori,
                'gambar' => $item3->gambar,                'seller_id' => $otherSeller->id,
                'seller_name' => $otherSeller->nama_toko
            ]],
            'created_at' => now()->subHours(5),
        ]);

        // --- DATA DUMMY 4: Transaksi Shipped ---
        $item4 = $allProducts->random();
        Transaction::create([
            'user_id' => $buyer->id,
            'seller_id' => $item4->user_id,
            'invoice_number' => 'INV-20260130-SHIPPED-4',
            'parent_invoice' => 'INV-20260130-SHIPPED-4',
            'total_price' => ($item4->harga * 1) + 20000 + 2500,
            'shipping_fee' => 20000,
            'admin_fee' => 2500,
            'status' => 'shipped',
            'payment_method' => 'midtrans',
            'alamat' => 'Perumahan Indah Blok C-4, Mojokerto',
            'courier_id' => $driver2->id ?? null, 
            'courier_service_id' => $jekpasMobil->id ?? null,
            'resi' => 'INTERNAL-JEKPAS-MOBIL-001',
            'items_details' => [[
                'id' => $item4->id,
                'nama_barang' => $item4->nama_barang, 
                'harga' => $item4->harga, 
                'quantity' => 1, 
                'total' => $item4->harga,
                'weight' => $item4->berat,
                'seller_id' => $item4->user_id,
                'seller_name' => $item4->user->nama_toko
            ]],
            'created_at' => now()->subDays(2),
        ]);

        // --- DATA DUMMY 5: Transaksi Completed ---
        $item5 = $allProducts->random();
        Transaction::create([
            'user_id' => $buyer->id,
            'seller_id' => $item5->user_id,
            'invoice_number' => 'INV-20260130-DONE-5',
            'parent_invoice' => 'INV-20260130-DONE-5',
            'total_price' => ($item5->harga * 3) + 8000 + 2500,
            'shipping_fee' => 8000,
            'admin_fee' => 2500,
            'status' => 'completed',
            'payment_method' => 'midtrans',
            'alamat' => 'Jl. Gajah Mada No. 100, Mojokerto',
            'courier_id' => $driver3->id ?? null, 
            'courier_service_id' => $kurirLokal->id ?? null,
            'items_details' => [[
                'id' => $item5->id,
                'nama_barang' => $item5->nama_barang, 
                'harga' => $item5->harga, 
                'quantity' => 3, 
                'total' => $item5->harga * 3,
                'weight' => $item5->berat,
                'seller_id' => $item5->user_id,
                'seller_name' => $item5->user->nama_toko
            ]],
            'completed_at' => now()->subMinutes(30),
            'created_at' => now()->subDays(3),
        ]);

        // --- DATA DUMMY 6: Transaksi Pending (Dinamis juga) ---
        $item6 = $allProducts->random();
        Transaction::create([
            'user_id' => $buyer->id,
            'seller_id' => $item6->user_id,
            'invoice_number' => 'INV-20260130-PENDING-6',
            'parent_invoice' => 'INV-20260130-PENDING-6',
            'total_price' => ($item6->harga * 3) + 5000 + 2500,
            'shipping_fee' => 5000,
            'admin_fee' => 2500,
            'status' => 'pending',
            'payment_method' => 'midtrans',
            'alamat' => 'Dusun Kedung Mlati, Mojokerto',
            'courier_service_id' => $jekpasMotor->id ?? null,
            'items_details' => [[
                'id' => $item6->id,
                'nama_barang' => $item6->nama_barang, 
                'harga' => $item6->harga, 
                'quantity' => 3, 
                'total' => $item6->harga * 3,
                'weight' => $item6->berat,
                'seller_id' => $item6->user_id,
                'seller_name' => $item6->user->nama_toko
            ]],
            'created_at' => now(),
        ]);

        // --- DATA DUMMY 7: Transaksi COD ---
        $item7 = $allProducts->random();
        Transaction::create([
            'user_id' => $buyer->id,
            'seller_id' => $item7->user_id,
            'invoice_number' => 'INV-20260130-COD-7',
            'parent_invoice' => 'INV-20260130-COD-7',
            'total_price' => ($item7->harga * 1) + 25000 + 2500,
            'shipping_fee' => 25000,
            'admin_fee' => 2500,
            'status' => 'waiting_confirmation',
            'payment_method' => 'cod',
            'alamat' => 'Magersari Gg. 3 No. 5, Mojokerto',
            'courier_service_id' => $kurirLokal->id ?? null,
            'items_details' => [[
                'id' => $item7->id,
                'nama_barang' => $item7->nama_barang, 
                'harga' => $item7->harga, 
                'quantity' => 1, 
                'total' => $item7->harga,
                'weight' => $item7->berat,
                'seller_id' => $item7->user_id,
                'seller_name' => $item7->user->nama_toko
            ]],
            'created_at' => now()->subHours(1),
        ]);
    }
}
