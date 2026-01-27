<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
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

    public function complete(Transaction $transaction)
    {
        if ($transaction->courier_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->status !== 'shipped') {
            return back()->with('error', 'Status pesanan tidak valid bolo!');
        }

        $transaction->completeTransaction();

        return back()->with('success', 'MANTAP BOLO! Pesanan berhasil diselesaikan.');
    }
}
