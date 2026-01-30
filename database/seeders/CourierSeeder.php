<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Instansi Jek-Pas (Layanan Motor)
        Courier::create([
            'name' => 'Jek-Pas',
            'service_name' => 'Motor',
            'multiplier' => 1.0,
            'base_extra_cost' => 0,
            'estimated_time' => '15-30 Menit',
            'is_active' => true,
            'description' => 'Pengiriman super cepat pake motor bolo',
            'max_distance' => 15,
            'max_weight' => 20
        ]);

        Courier::create([
            'name' => 'Jek-Pas',
            'service_name' => 'Mobil',
            'multiplier' => 1.5,
            'base_extra_cost' => 5000,
            'estimated_time' => '30-45 Menit',
            'is_active' => true,
            'description' => 'Kirim barang besar pake mobil Jek-Pas',
            'max_distance' => 25,
            'max_weight' => 200
        ]);

        // 2. Kurir Lokal Mojokerto
        Courier::create([
            'name' => 'Kurir Mojokerto',
            'service_name' => 'Express',
            'multiplier' => 1.2,
            'base_extra_cost' => 2000,
            'estimated_time' => '1 Jam Sampai',
            'is_active' => true,
            'description' => 'Kurir lokal asli Mojokerto',
            'max_distance' => 20,
            'max_weight' => 50
        ]);
    }
}
