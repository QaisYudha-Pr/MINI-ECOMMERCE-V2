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
            'membeli-produk'

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

        $permissions = Permission::all()->except(Permission::where('name', 'membeli-produk')->first()->id);
        $roleAdmin->givePermissionTo($permissions);


        $roleUser->givePermissionTo([
            'lihat-produk',
            'tambah-produk',
            'edit-produk',
            'hapus-produk',
            'membeli-produk',
        ]);
    }
}
