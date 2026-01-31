<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Transaction::where('courier_id', Auth::id())
            ->whereIn('status', ['shipped', 'completed'])
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('courier.deliveries.index', compact('deliveries'));
    }

    public function complete(Request $request, Transaction $transaction)
    {
        if ($transaction->courier_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->status !== 'shipped') {
            return back()->with('error', 'Status pesanan tidak valid bolo!');
        }

        $request->validate([
            'delivery_proof' => 'required|image|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('delivery_proof')) {
            $file = $request->file('delivery_proof');
            $filename = 'proof_' . $transaction->invoice_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/proofs'), $filename);
            $proofPath = 'uploads/proofs/' . $filename;
        }

        $transaction->update([
            'status' => 'delivered',
            'delivery_proof' => $proofPath
        ]);

        // Kirim Notifikasi ke User bolo
        Notification::create([
            'user_id' => $transaction->user_id,
            'title' => 'PESANAN SAMPAI BOLO!',
            'message' => 'Pesanan #' . $transaction->invoice_number . ' sudah diantar oleh kurir. Silakan cek dan konfirmasi ya!',
            'type' => 'success',
            'is_read' => false
        ]);

        return back()->with('success', 'MANTAP BOLO! Pesanan berhasil diantar. Bukti foto telah diunggah.');
    }
}
