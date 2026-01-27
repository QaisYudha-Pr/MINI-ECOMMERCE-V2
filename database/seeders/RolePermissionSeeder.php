<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'tambah-user',
            'edit-user',
            'hapus-user',
            'lihat-user',
            'tambah-produk',
            'edit-produk',
            'hapus-produk',
            'lihat-produk',
            'edit-permissions',
            'lihat-permissions',
            'membeli-produk',
            'mengantar-pesanan'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $roleAdmin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $roleUser = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        $roleSeller = Role::firstOrCreate([
            'name' => 'seller',
            'guard_name' => 'web',
        ]);

        $roleCourier = Role::firstOrCreate([
            'name' => 'courier',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::all();
        // Admin gets everything
        $roleAdmin->syncPermissions($permissions);

        // Seller gets product management + viewing
        $roleSeller->syncPermissions([
            'lihat-produk',
            'tambah-produk',
            'edit-produk',
            'hapus-produk',
        ]);

        // User only gets buying and viewing
        $roleUser->syncPermissions([
            'lihat-produk',
            'membeli-produk',
        ]);

        // Courier gets delivery
        $roleCourier->syncPermissions([
            'mengantar-pesanan',
        ]);
    }
}
