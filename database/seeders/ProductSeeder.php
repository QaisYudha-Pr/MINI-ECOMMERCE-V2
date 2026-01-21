<?php

namespace Database\Seeders;

use App\Models\ItemShop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user dengan role seller
        $sellers = User::role('seller')->get();
        
        // Jika tidak ada seller sama sekali, fallback ke admin atau user pertama
        if ($sellers->isEmpty()) {
            $sellers = collect([User::where('email', 'admin@gmail.com')->first() ?? User::first()]);
        }

        $products = [
            [
                'nama_barang' => 'Xiaomi POCO C71 4/128GB - Official Store',
                'deskripsi' => 'Smartphone powerfull dengan harga terjangkau.',
                'harga' => 1422000,
                'stok' => 50,
                'total_terjual' => 1250,
                'kategori' => 'elektronik',
                'lokasi' => 'Jakarta Barat',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => '[FREE CASE] Baseus BP1 Pro Wireless Earbuds',
                'deskripsi' => 'Earbuds stylish dengan suara bass yang mantap.',
                'harga' => 309000,
                'stok' => 100,
                'total_terjual' => 8400,
                'kategori' => 'elektronik',
                'lokasi' => 'Tangerang',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'PROMO 6 Pack Tissu Paseo Smart 250 Sheets',
                'deskripsi' => 'Tissu lembut dan kuat untuk kebutuhan keluarga.',
                'harga' => 15998,
                'stok' => 500,
                'total_terjual' => 15000,
                'kategori' => 'kebutuhan-harian',
                'lokasi' => 'Bekasi',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'EIGER MIGRATES PACK 15L - Backpack Outdoor',
                'deskripsi' => 'Tas punggung tangguh untuk petualangan harian.',
                'harga' => 231750,
                'stok' => 20,
                'total_terjual' => 450,
                'kategori' => 'fashion',
                'lokasi' => 'Bandung',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Mesin Steam Cuci Mobil Jet Cleaner Lakoni',
                'deskripsi' => 'Alat cuci mobil bertenaga tinggi.',
                'harga' => 995000,
                'stok' => 15,
                'total_terjual' => 320,
                'kategori' => 'otomotif',
                'lokasi' => 'Surabaya',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Mini Proyektor LED YG300 Portable Cinema',
                'deskripsi' => 'Nonton bioskop di rumah makin seru.',
                'harga' => 76146,
                'stok' => 45,
                'total_terjual' => 5600,
                'kategori' => 'elektronik',
                'lokasi' => 'Jakarta Pusat',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Sunlight Pencuci Piring Jeruk Nipis Refill',
                'deskripsi' => 'Bersihkan lemak membandel seketika.',
                'harga' => 12500,
                'stok' => 300,
                'total_terjual' => 25000,
                'kategori' => 'kebutuhan-harian',
                'lokasi' => 'Medan',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Baseus Encok WM01 Bluetooth Earphone',
                'deskripsi' => 'Earphone mungil dengan koneksi stabil.',
                'harga' => 165000,
                'stok' => 80,
                'total_terjual' => 9200,
                'kategori' => 'elektronik',
                'lokasi' => 'Jakarta Timur',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Rak Susun Serbaguna 3 Tingkat Dengan Roda',
                'deskripsi' => 'Organisir barang makin mudah.',
                'harga' => 85000,
                'stok' => 30,
                'total_terjual' => 150,
                'kategori' => 'peralatan-rumah',
                'lokasi' => 'Depok',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Sandal Slop Pria Casual Distro Terbaru',
                'deskripsi' => 'Sandal nyaman untuk santai.',
                'harga' => 25000,
                'stok' => 150,
                'total_terjual' => 2400,
                'kategori' => 'fashion',
                'lokasi' => 'Malang',
                'gambar' => 'image/jin.jpg',
            ],
        ];

        foreach ($products as $p) {
            ItemShop::updateOrCreate(
                ['nama_barang' => $p['nama_barang']],
                array_merge($p, ['user_id' => $sellers->random()->id])
            );
        }
    }
}
