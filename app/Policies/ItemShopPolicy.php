<?php

namespace App\Policies;

use App\Models\ItemShop;
use App\Models\User;

class ItemShopPolicy
{
    public function viewAny(?User $user)
    {
        return true; // guest boleh lihat
    }

    public function view(?User $user, ItemShop $item)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'user']);
    }

    public function update(User $user, ItemShop $nama_barang)
    {
        return $user->hasRole('admin') || $nama_barang->user_id === $user->id;
    }

    public function delete(User $user, ItemShop $nama_barang)
    {
        return $user->hasRole('admin') || $nama_barang->user_id === $user->id;
    }
}
