<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $couriers = User::role('courier')->get();

        $query = Transaction::query()->with(['user', 'courier']);

        // Semua user (Admin maupun Seller) hanya bisa melihat transaksi yang berisi produk milik mereka
        // Ini untuk menjaga privasi antar penjual, termasuk admin jika dia tidak menjual barang tersebut
        $myProductIds = \App\Models\ItemShop::where('user_id', $user->id)->pluck('id')->toArray();
        
        $query->where(function($q) use ($myProductIds) {
            foreach ($myProductIds as $id) {
                $q->orWhereJsonContains('items_details', [['id' => $id]]);
            }
        });

        $transactions = $query->latest()->paginate(10);

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

        return back()->with('success', 'Resi berhasil diupdate, status berubah jadi Shipped!');
    }
}
