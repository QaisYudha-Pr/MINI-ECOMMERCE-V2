<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function create()
    {
        // If user is already a seller, redirect them
        if (Auth::user()->hasRole('seller')) {
            return redirect()->route('dashboard')->with('success', 'You are already a seller!');
        }

        return view('seller.register-seller');
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
