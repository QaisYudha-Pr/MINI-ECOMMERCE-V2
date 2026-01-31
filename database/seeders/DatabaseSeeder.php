<?php

namespace Database\Seeders;
use Database\Seeders\ItemShopSeeder;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call(RolePermissionSeeder::class);
    $this->call(CourierSeeder::class); // Harus sebelum UserSeeder karena ada instansi_kurir_id
    $this->call(UserSeeder::class);
    $this->call(ProductSeeder::class);
    // $this->call(TransactionSeeder::class);
}

}
