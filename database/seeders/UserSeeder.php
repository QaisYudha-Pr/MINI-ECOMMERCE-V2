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
            'alamat' => 'Kecamatan Magersari, Mojokerto',
            'latitude' => -7.4682,
            'longitude' => 112.4501,
            'seller_status' => 'none',
        ]);
        $user->assignRole('user');

        // Tambah satu seller khusus untuk testing variasi lokasi
        $seller = User::create([
            'name' => 'Bakol Nyeni',
            'email' => 'seller@gmail.com',
            'password' => bcrypt('seller1234'),
            'nama_toko' => 'Toko Seje Dewe',
            'alamat' => 'Sooko, Kabupaten Mojokerto',
            'latitude' => -7.4921,
            'longitude' => 112.4135,
            'seller_status' => 'approved',
        ]);
        $seller->assignRole('user');
        $seller->assignRole('seller');

        $courier = User::create([
            'name' => 'Driver JekPas 1',
            'email' => 'driver1@gmail.com',
            'password' => bcrypt('driver1234'),
            'alamat' => 'Basecamp JekPas Mojokerto',
            'latitude' => -7.4726,
            'longitude' => 112.4382,
            'courier_agency_id' => 1, // Ikut Jek-Pas (Motor)
        ]);
        $courier->assignRole('courier');

        $courier2 = User::create([
            'name' => 'Driver JekPas 2',
            'email' => 'driver2@gmail.com',
            'password' => bcrypt('driver1234'),
            'alamat' => 'Basecamp JekPas Mojokerto',
            'latitude' => -7.4726,
            'longitude' => 112.4382,
            'courier_agency_id' => 2, // Ikut Jek-Pas (Mobil)
        ]);
        $courier2->assignRole('courier');

        $courier3 = User::create([
            'name' => 'Driver Lokal 1',
            'email' => 'driver3@gmail.com',
            'password' => bcrypt('driver1234'),
            'alamat' => 'Magersari, Mojokerto',
            'latitude' => -7.4726,
            'longitude' => 112.4382,
            'courier_agency_id' => 3, // Ikut Kurir Mojokerto
        ]);
        $courier3->assignRole('courier');
    }
}
