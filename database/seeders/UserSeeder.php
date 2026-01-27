<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        app(PermissionRegistrar::class)->forgetCachedPermissions();



        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin1234'),
            'nama_toko' => 'Admin Official Store',
            'alamat' => 'Pusat Kota Mojokerto, Jawa Timur',
            'latitude' => -7.4726,
            'longitude' => 112.4382,
            'seller_status' => 'approved',
        ]);
        $admin->assignRole('admin');
        $admin->assignRole('seller');

        $user = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('user1234'),
            'nama_toko' => 'Bolo Shop Mojokerto',
            'alamat' => 'Kecamatan Magersari, Mojokerto',
            'latitude' => -7.4682,
            'longitude' => 112.4501,
            'seller_status' => 'none',
        ]);
        $user->assignRole('user');

        // Tambah satu seller khusus untuk testing variasi lokasi
        $seller = User::create([
            'name' => 'Penjual Jauh',
            'email' => 'seller@gmail.com',
            'password' => bcrypt('seller1234'),
            'nama_toko' => 'Toko Jauh Banget',
            'alamat' => 'Sooko, Kabupaten Mojokerto',
            'latitude' => -7.4921,
            'longitude' => 112.4135,
            'seller_status' => 'approved',
        ]);
        $seller->assignRole('user');
        $seller->assignRole('seller');

        $courier = User::create([
            'name' => 'Kurir Bolo',
            'email' => 'kurir@gmail.com',
            'password' => bcrypt('kurir1234'),
            'alamat' => 'Pool Kurir Mojokerto',
            'latitude' => -7.4726,
            'longitude' => 112.4382,
        ]);
        $courier->assignRole('courier');
    }
}
