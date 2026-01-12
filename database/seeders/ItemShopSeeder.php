<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemShopSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('item_shops')->insert([
            [
                'nama_barang' => 'Laptop ASUS Vivobook 14',
                'harga' => 7500000,
                'deskripsi' => 'Laptop ringan untuk kerja dan kuliah',
                'gambar' => '/jarvis.jpg',
                'user_id' => 1

            ],
            [
                'nama_barang' => 'Mouse Logitech M170',
                'harga' => 150000,
                'deskripsi' => 'Mouse wireless hemat baterai',
                'gambar' => '/jarvis.jpg',
                'user_id' => 1

            ],
            [
                'nama_barang' => 'Keyboard Mechanical Red Switch',
                'harga' => 350000,
                'deskripsi' => 'Keyboard mechanical nyaman untuk ngetik',
                'gambar' => '/jarvis.jpg',
                'user_id' => 1
            ],
            [
                'nama_barang' => 'Monitor LG 24 Inch',
                'harga' => 1800000,
                'deskripsi' => 'Monitor Full HD untuk kerja dan gaming',
                'gambar' => '/jarvis.jpg',
                'user_id' => 1
            ],
            [
                'nama_barang' => 'Headset Gaming Rexus',
                'harga' => 250000,
                'deskripsi' => 'Headset gaming dengan mic jernih',
                'gambar' => '/jarvis.jpg',
                'user_id' => 1
            ],
            [
                'nama_barang' => 'Flashdisk Sandisk 64GB',
                'harga' => 120000,
                'deskripsi' => 'Flashdisk USB 3.0 kecepatan tinggi',
                'gambar' => '/jarvis.jpg',
                'user_id' => 2
            ],
            [
                'nama_barang' => 'SSD NVMe 512GB',
                'harga' => 900000,
                'deskripsi' => 'SSD NVMe performa tinggi',
                'gambar' => '/jarvis.jpg',
                'user_id' => 2
            ],
            [
                'nama_barang' => 'Printer Epson L3210',
                'harga' => 2200000,
                'deskripsi' => 'Printer ink tank hemat tinta',
                'gambar' => '/jarvis.jpg',
                'user_id' => 2
            ],
        ]);
    }
}
