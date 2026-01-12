<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ItemShop;

class ItemShopSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            return;
        }

        $data = [
            [
                'nama_barang' => 'Laptop ASUS Vivobook 14',
                'harga' => 7500000,
                'deskripsi' => 'Laptop ringan dengan prosesor terbaru, cocok untuk kerja dan kuliah. Dilengkapi dengan layar 14 inci Full HD dan baterai tahan 8 jam.',
                'gambar' => 'uploads/sample_1.jpg',
                'user_id' => $users->first()->id,
            ],
            [
                'nama_barang' => 'Mouse Logitech M170',
                'harga' => 150000,
                'deskripsi' => 'Mouse wireless hemat baterai dengan jangkauan 10 meter. Ergonomis dan nyaman untuk penggunaan sehari-hari.',
                'gambar' => 'uploads/sample_2.jpg',
                'user_id' => $users->first()->id,
            ],
            [
                'nama_barang' => 'Keyboard Mechanical Red Switch',
                'harga' => 350000,
                'deskripsi' => 'Keyboard mechanical dengan switch merah yang responsif. Cocok untuk gaming dan typing profesional.',
                'gambar' => 'uploads/sample_3.jpg',
                'user_id' => $users->first()->id,
            ],
            [
                'nama_barang' => 'Monitor LG 24 Inch',
                'harga' => 1800000,
                'deskripsi' => 'Monitor Full HD dengan panel IPS, cocok untuk kerja dan gaming. Memiliki brightness 300 nits.',
                'gambar' => 'uploads/sample_4.jpg',
                'user_id' => $users->first()->id,
            ],
            [
                'nama_barang' => 'Headset Gaming Rexus',
                'harga' => 250000,
                'deskripsi' => 'Headset gaming dengan mikrofon jernih dan noise cancellation. Nyaman dipakai untuk sesi gaming panjang.',
                'gambar' => 'uploads/sample_5.jpg',
                'user_id' => $users->skip(1)->first()->id ?? $users->first()->id,
            ],
            [
                'nama_barang' => 'Flashdisk Sandisk 64GB',
                'harga' => 120000,
                'deskripsi' => 'Flashdisk USB 3.0 dengan kecepatan transfer tinggi. Cocok untuk menyimpan data penting.',
                'gambar' => 'uploads/sample_6.jpg',
                'user_id' => $users->skip(1)->first()->id ?? $users->first()->id,
            ],
            [
                'nama_barang' => 'SSD NVMe 512GB',
                'harga' => 900000,
                'deskripsi' => 'SSD NVMe dengan performa tinggi, kecepatan baca 3500 MB/s. Ideal untuk sistem operasi dan gaming.',
                'gambar' => 'uploads/sample_7.jpg',
                'user_id' => $users->skip(1)->first()->id ?? $users->first()->id,
            ],
            [
                'nama_barang' => 'Printer Epson L3210',
                'harga' => 2200000,
                'deskripsi' => 'Printer ink tank dengan sistem hemat tinta. Biaya cetak per halaman sangat murah, ideal untuk kantor kecil.',
                'gambar' => 'uploads/sample_8.jpg',
                'user_id' => $users->skip(1)->first()->id ?? $users->first()->id,
            ],
        ];

        foreach ($data as $item) {
            ItemShop::create($item);
        }
    }
}
