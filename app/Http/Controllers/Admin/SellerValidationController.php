<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SellerValidationController extends Controller
{
    public function index()
    {
        $pendingSellers = User::where('seller_status', 'pending')->latest()->get();
        return view('admin.sellers-accept.index', compact('pendingSellers'));
    }

    public function approve(User $user)
    {
        $user->update([
            'seller_status' => 'approved'
        ]);
        
        if (!$user->hasRole('seller')) {
            $user->assignRole('seller');
        }

        return redirect()->back()->with('success', "Toko {$user->nama_toko} telah disetujui!");
    }

    public function reject(User $user)
    {
        $user->update([
            'seller_status' => 'rejected'
        ]);

        return redirect()->back()->with('error', "Pendaftaran toko {$user->nama_toko} ditolak.");
    }
}
