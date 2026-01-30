<?php

namespace App\Http\Controllers\Shop\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if ($user->hasRole('seller')) {
            return redirect()->route('dashboard')->with('success', 'Anda sudah terdaftar sebagai seller!');
        }

        if ($user->seller_status === 'pending') {
            return view('shop.user.seller-pending');
        }

        return view('shop.user.seller-register');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->seller_status === 'pending') {
            return redirect()->back()->with('error', 'Pendaftaran Anda sedang dalam proses.');
        }

        $request->validate([
            'nama_toko' => 'required|string|max:255|unique:users,nama_toko',
            'phone' => 'required|string|max:20',
            'seller_document' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $documentPath = null;
        if ($request->hasFile('seller_document')) {
            $file = $request->file('seller_document');
            $filename = 'ktp_' . time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/documents'), $filename);
            $documentPath = 'uploads/documents/' . $filename;
        }

        $user->update([
            'nama_toko' => $request->nama_toko,
            'phone' => $request->phone,
            'seller_document' => $documentPath,
            'seller_status' => 'pending',
        ]);

        return redirect()->route('seller.create')->with('success', 'Pendaftaran berhasil dikirim! Mohon tunggu validasi dari tim admin kami.');
    }
}
