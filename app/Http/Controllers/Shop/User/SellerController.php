<?php

namespace App\Http\Controllers\Shop\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function create()
    {
        if (Auth::user()->hasRole('seller')) {
            return redirect()->route('dashboard')->with('success', 'You are already a seller!');
        }

        return view('shop.user.seller-register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255|unique:users,nama_toko',
        ]);

        $user = Auth::user();
        $user->update([
            'nama_toko' => $request->nama_toko,
        ]);
        
        $user->assignRole('seller');

        return redirect()->route('dashboard')->with('success', 'Congratulations! You are now a registered Seller.');
    }
}
