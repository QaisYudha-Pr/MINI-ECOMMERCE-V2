<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

class VoucherPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->seller_status === 'approved';
    }

    public function view(User $user, Voucher $voucher): bool
    {
        if ($user->hasRole('admin')) return true;
        return $voucher->seller_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->seller_status === 'approved';
    }

    public function update(User $user, Voucher $voucher): bool
    {
        if ($user->hasRole('admin')) return true;
        return $voucher->seller_id === $user->id;
    }

    public function delete(User $user, Voucher $voucher): bool
    {
        if ($user->hasRole('admin')) return true;
        return $voucher->seller_id === $user->id;
    }
}
