<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        Courier::truncate();

        // 1. KILAT (Jek-Pas Motor - Super Fast)
        Courier::create([
            'name' => 'Jek-Pas',
            'service_name' => 'KILAT',
            'multiplier' => 1.2,
            'base_extra_cost' => 3000,
            'estimated_time' => '15-30 Menit',
            'is_active' => true,
            'description' => 'Prioritas utama bolo, langsung jemput langsung kirim!',
            'max_distance' => 10,
            'max_weight' => 20
        ]);

        // 2. MOTOR (Standar)
        Courier::create([
            'name' => 'Kurir Mojokerto',
            'service_name' => 'MOTOR',
            'multiplier' => 1.0,
            'base_extra_cost' => 0,
            'estimated_time' => '1-3 Jam Sampai',
            'is_active' => true,
            'description' => 'Pengiriman motor reguler, harga pas di kantong.',
            'max_distance' => 20,
            'max_weight' => 25 
        ]);

        // 3. MOBIL (Muat Besar)
        Courier::create([
            'name' => 'Jek-Pas',
            'service_name' => 'MOBIL',
            'multiplier' => 1.5,
            'base_extra_cost' => 5000,
            'estimated_time' => 'Sameday',
            'is_active' => true,
            'description' => 'Khusus barang berat atau belanjaan banyak pake mobil bolo.',
            'max_distance' => 30,
            'max_weight' => 500
        ]);
    }
}
