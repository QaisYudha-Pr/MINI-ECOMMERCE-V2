<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        
        $query = Transaction::with(['user', 'seller']);

        // Filter by Seller if not Admin
        if (!$isAdmin) {
            $query->where('seller_id', $user->id);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Date Filter
        if ($request->filled('date_filter')) {
            $days = (int)$request->date_filter;
            $query->where('created_at', '>=', now()->subDays($days));
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();
        $couriers = User::role('courier')->get();

        return view('admin.transactions.index', compact('transactions', 'couriers'));
    }

    public function show(Transaction $transaction)
    {
        $user = auth()->user();
        
        // Proteksi Logic
        if ($transaction->seller_id !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'Akses ditolak bolo!');
        }

        $couriers = User::role('courier')->get();
        
        return view('admin.transactions.show', compact('transaction', 'couriers'));
    }

    public function updateCourier(Request $request, Transaction $transaction)
    {
        $request->validate([
            'courier_id' => 'required|exists:users,id',
        ]);

        $transaction->update([
            'courier_id' => $request->courier_id,
            'status' => 'shipped', // Otomatis set status ke shipped saat assign kurir
            'resi' => 'MJK-' . strtoupper(str()->random(8)), // Prefix Mojokerto bolo
        ]);

        // Notif Kurir (Biar kurirnya tahu ada job)
        Notification::create([
            'user_id' => $request->courier_id,
            'title' => 'Ada Tugas Baru, Bolo! 🛵',
            'message' => "Segera jemput paket #{$transaction->invoice_number} di toko {$transaction->seller->name}.",
            'type' => 'info'
        ]);

        // Notif User
        Notification::create([
            'user_id' => $transaction->user_id,
            'title' => 'Pesanan Dikirim! 🚚',
            'message' => "Hahaha asik bolo! Pesanan #{$transaction->invoice_number} lagi meluncur ke tempatmu dibawa kurir internal.",
            'type' => 'info'
        ]);

        return back()->with('success', 'Kurir internal berhasil ditugaskan & status berubah jadi Shipped!');
    }

    public function updateResi(Request $request, Transaction $transaction)
    {
        $request->validate([
            'resi' => 'required|string|max:100',
        ]);

        $transaction->update([
            'resi' => $request->resi,
            'status' => 'shipped',
        ]);

        // Notif User
        Notification::create([
            'user_id' => $transaction->user_id,
            'title' => 'Resi Diupdate 📦',
            'message' => "Bolo! Resi pesanan #{$transaction->invoice_number} sudah ada, sekarang lagi dikirim ya.",
            'type' => 'info'
        ]);

        return back()->with('success', 'Resi berhasil diupdate, status berubah jadi Shipped!');
    }

    public function confirmCod(Transaction $transaction)
    {
        if ($transaction->status !== 'waiting_confirmation') {
            return back()->with('error', 'Status pesanan tidak valid bolo!');
        }

        $transaction->update(['status' => 'paid']); // Set ke paid agar bisa diatur pengirimannya

        Notification::create([
            'user_id' => $transaction->user_id,
            'title' => 'Pesanan COD Dikonfirmasi! ✅',
            'message' => "Horee bolo! Pesanan COD #{$transaction->invoice_number} sudah dikonfirmasi penjual dan sedang diproses.",
            'type' => 'success'
        ]);

        return back()->with('success', 'Pesanan COD berhasil dikonfirmasi bolo!');
    }
}
