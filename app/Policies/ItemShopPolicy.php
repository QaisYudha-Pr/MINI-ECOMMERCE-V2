<?php

namespace App\Policies;

use App\Models\ItemShop;
use App\Models\User;

class ItemShopPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasRole('admin')
            || $user->hasRole('seller')
            || $user->hasPermissionTo('lihat-produk');
    }

    public function view(?User $user, ItemShop $item)
    {
        return true; // Semua orang bisa lihat detail produk (public)
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('tambah-produk');
    }

    public function update(User $user, ItemShop $item)
    {
        return $user->hasRole('admin')
            || $user->hasPermissionTo('edit-produk')
            || $item->user_id === $user->id;
    }

    public function delete(User $user, ItemShop $item)
    {
        return $user->hasRole('admin')
            || $user->hasPermissionTo('hapus-produk')
            || $item->user_id === $user->id;
    }
}
