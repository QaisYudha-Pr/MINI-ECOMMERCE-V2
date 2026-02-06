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
        
        $query = Transaction::query()->with(['user', 'seller', 'courier', 'courierService']);

        // Proteksi Logic: Admin atau Seller hanya boleh lihat orderan yang masuk ke tokonya sendiri.
        // Gak perlu ngintip tetangga bolo, fokus urus pesanan dewe-dewe biar berkah!
        $query->where('seller_id', $user->id);

        // Filter Status (Sync logic with buyer side)
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'pending') {
                $query->whereIn('status', ['pending', 'paid', 'waiting_confirmation']);
            } elseif ($status === 'success') {
                $query->whereIn('status', ['success', 'shipped', 'delivered']);
            } elseif ($status === 'completed') {
                $query->where('status', 'completed');
            } else {
                $query->where('status', $status);
            }
        }

        // Search Logic
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('items_details', 'like', "%{$search}%");
            });
        }

        // Date Filter Logic
        if ($request->filled('date_filter')) {
            $days = (int)$request->date_filter;
            $query->where('created_at', '>=', now()->subDays($days));
        }

        $transactions = $query->latest()->paginate(10);
        
        // Ambil semua kurir (untuk Admin/Seller menugaskan)
        $couriers = User::role('courier')->get();

        return view('admin.transactions.index', compact('transactions', 'couriers'));
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
}
