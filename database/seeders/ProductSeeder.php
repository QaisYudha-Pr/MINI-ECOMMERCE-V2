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
                'berat' => 500,
                'total_terjual' => 1250,
                'kategori' => 'elektronik',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => '[FREE CASE] Baseus BP1 Pro Wireless Earbuds',
                'deskripsi' => 'Earbuds stylish dengan suara bass yang mantap.',
                'harga' => 309000,
                'stok' => 100,
                'berat' => 200,
                'total_terjual' => 8400,
                'kategori' => 'elektronik',
                'gambar' => 'image/jun.jpg',
            ],
            [
                'nama_barang' => 'PROMO 6 Pack Tissu Paseo Smart 250 Sheets',
                'deskripsi' => 'Tissu lembut dan kuat untuk kebutuhan keluarga.',
                'harga' => 15998,
                'stok' => 500,
                'berat' => 1200,
                'total_terjual' => 15000,
                'kategori' => 'kebutuhan-harian',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'EIGER MIGRATES PACK 15L - Backpack Outdoor',
                'deskripsi' => 'Tas punggung tangguh untuk petualangan harian.',
                'harga' => 231750,
                'stok' => 20,
                'berat' => 800,
                'total_terjual' => 450,
                'kategori' => 'fashion',
                'gambar' => 'image/jun.jpg',
            ],
            [
                'nama_barang' => 'Mesin Steam Cuci Mobil Jet Cleaner Lakoni',
                'deskripsi' => 'Alat cuci mobil bertenaga tinggi.',
                'harga' => 995000,
                'stok' => 15,
                'berat' => 7000,
                'total_terjual' => 320,
                'kategori' => 'otomotif',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Mini Proyektor LED YG300 Portable Cinema',
                'deskripsi' => 'Nonton bioskop di rumah makin seru.',
                'harga' => 76146,
                'stok' => 45,
                'berat' => 600,
                'total_terjual' => 5600,
                'kategori' => 'elektronik',
                'gambar' => 'image/jun.jpg',
            ],
            [
                'nama_barang' => 'Sunlight Pencuci Piring Jeruk Nipis Refill',
                'deskripsi' => 'Bersihkan lemak membandel seketika.',
                'harga' => 12500,
                'stok' => 300,
                'berat' => 800,
                'total_terjual' => 25000,
                'kategori' => 'kebutuhan-harian',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Baseus Encok WM01 Bluetooth Earphone',
                'deskripsi' => 'Earphone mungil dengan koneksi stabil.',
                'harga' => 165000,
                'stok' => 80,
                'berat' => 150,
                'total_terjual' => 9200,
                'kategori' => 'elektronik',
                'gambar' => 'image/jun.jpg',
            ],
            [
                'nama_barang' => 'Rak Susun Serbaguna 3 Tingkat Dengan Roda',
                'deskripsi' => 'Organisir barang makin mudah.',
                'harga' => 85000,
                'stok' => 30,
                'total_terjual' => 150,
                'kategori' => 'peralatan-rumah',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Sandal Slop Pria Casual Distro Terbaru',
                'deskripsi' => 'Sandal nyaman untuk santai.',
                'harga' => 25000,
                'stok' => 150,
                'total_terjual' => 2400,
                'kategori' => 'fashion',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Sepatu Compass Gazelle Low Black White',
                'deskripsi' => 'Sepatu kanvas lokal berkualitas tinggi.',
                'harga' => 408000,
                'stok' => 25,
                'berat' => 1100,
                'total_terjual' => 1200,
                'kategori' => 'fashion',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Erigo T-Shirt Project Summer Black',
                'deskripsi' => 'Kaos santai dengan desain summer yang keren.',
                'harga' => 85000,
                'stok' => 150,
                'berat' => 250,
                'total_terjual' => 5400,
                'kategori' => 'fashion',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Logitech G304 LightSpeed Wireless Gaming Mouse',
                'deskripsi' => 'Mouse gaming wireless dengan sensor HERO.',
                'harga' => 599000,
                'stok' => 40,
                'berat' => 300,
                'total_terjual' => 2100,
                'kategori' => 'elektronik',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Indomie Goreng Spesial 1 Dus (40 Pcs)',
                'deskripsi' => 'Mie instan favorit sejuta umat.',
                'harga' => 115000,
                'stok' => 100,
                'berat' => 3500,
                'total_terjual' => 800,
                'kategori' => 'kebutuhan-harian',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Helm KYT DJ Maru Solid World Class Helmet',
                'deskripsi' => 'Helm half face standar DOT dan SNI.',
                'harga' => 350000,
                'stok' => 30,
                'berat' => 2000,
                'total_terjual' => 1800,
                'kategori' => 'otomotif',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Oli Mesin Yamalube Super Sport 1L',
                'deskripsi' => 'Oli sintetik untuk performa maksimal motor sport.',
                'harga' => 95000,
                'stok' => 50,
                'berat' => 1000,
                'total_terjual' => 950,
                'kategori' => 'otomotif',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Sandisk Ultra MicroSDXC 128GB 140MB/s',
                'deskripsi' => 'Penyimpanan tambahan cepat untuk smartphone/kamera.',
                'harga' => 185000,
                'stok' => 200,
                'berat' => 50,
                'total_terjual' => 12000,
                'kategori' => 'elektronik',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'SKINTIFIC 5X Ceramide Barrier Repair Moisture Gel',
                'deskripsi' => 'Moisturizer ampuh untuk memperbaiki skin barrier.',
                'harga' => 135000,
                'stok' => 120,
                'berat' => 100,
                'total_terjual' => 45000,
                'kategori' => 'kesehatan-kecantikan',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Wardah Lightening Day Gel 30g',
                'deskripsi' => 'Pelembab wajah dengan tekstur gel yang ringan.',
                'harga' => 45000,
                'stok' => 80,
                'berat' => 150,
                'total_terjual' => 15000,
                'kategori' => 'kesehatan-kecantikan',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Philips Raket Nyamuk LED Charger',
                'deskripsi' => 'Basmi nyamuk dengan sekali ayun.',
                'harga' => 75000,
                'stok' => 60,
                'berat' => 800,
                'total_terjual' => 3200,
                'kategori' => 'kebutuhan-harian',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Buku Tulis Sinar Dunia (SIDU) 38 Lembar 1 Pack',
                'deskripsi' => 'Buku tulis berkualitas untuk sekolah.',
                'harga' => 38000,
                'stok' => 40,
                'berat' => 1200,
                'total_terjual' => 500,
                'kategori' => 'office-school',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Sharp Air Purifier FP-J30Y-B',
                'deskripsi' => 'Udara bersih dan sehat di dalam ruangan.',
                'harga' => 1250000,
                'stok' => 10,
                'berat' => 6000,
                'total_terjual' => 150,
                'kategori' => 'elektronik',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Sanken Dispenser Air HWD-730',
                'deskripsi' => 'Dispenser air praktis dengan kran panas dan dingin.',
                'harga' => 850000,
                'stok' => 12,
                'berat' => 12000,
                'total_terjual' => 200,
                'kategori' => 'elektronik',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Kopi Kapal Api Spesial Mix 1 Pack (20 Sachet)',
                'deskripsi' => 'Kopi instan dengan rasa mantap.',
                'harga' => 24500,
                'stok' => 200,
                'berat' => 600,
                'total_terjual' => 12000,
                'kategori' => 'food-beverage',
                'gambar' => 'image/jin.jpg',
            ],
            [
                'nama_barang' => 'Minyak Goreng Filma 2 Liter Refill',
                'deskripsi' => 'Minyak goreng jernih berkualitas tinggi.',
                'harga' => 38500,
                'stok' => 150,
                'berat' => 2000,
                'total_terjual' => 25000,
                'kategori' => 'kebutuhan-harian',
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
