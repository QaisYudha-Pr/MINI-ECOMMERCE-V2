<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = Transaction::query()->with(['user', 'seller', 'courier', 'courierService']);

        // Jika bukan admin beneran, hanya bisa lihat pesanan yang seller_id-nya adalah dia
        if (!$user->hasRole('admin')) {
            $query->where('seller_id', $user->id);
        }

        $transactions = $query->latest()->paginate(10);
        
        // Ambil semua kurir (untuk Admin)
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
            'resi' => 'INTERNAL-' . strtoupper(str()->random(8)), // Generate resi internal otomatis
        ]);

        // Notif User
        Notification::create([
            'user_id' => $transaction->user_id,
            'title' => 'Pesanan Dikirim! 🚚',
            'message' => "Hahaha asik bolo! Pesanan #{$transaction->invoice_number} lagi meluncur ke tempatmu.",
            'type' => 'info'
        ]);

        return back()->with('success', 'Kurir berhasil ditugaskan & status berubah jadi Shipped!');
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
