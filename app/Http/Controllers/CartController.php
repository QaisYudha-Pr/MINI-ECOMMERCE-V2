<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Item;

class CartController extends Controller
{
    public function checkout(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Login dulu bolo!'], 401);
        }

        // 1. Mulai transaksi database biar aman (kalau satu gagal, semua batal)
        \DB::beginTransaction();

        try {
            // 2. Simpan Transaksi ke Database
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . strtoupper(\Str::random(10)),
                'total_price' => $request->total_price,
                'status' => 'pending',
                'items_details' => $request->items, // Karena sudah ada $casts di model, langsung lempar array aja
            ]);

            // 3. LOGIKA POTONG STOK
            foreach ($request->items as $cartItem) {
                $item = Item::find($cartItem['id']);
                if ($item) {
                    // Kurangi stok sesuai jumlah yang dibeli (asumsi beli 1 per klik)
                    // Kalau ada field qty di keranjang, ganti angka 1 jadi qty-nya
                    $item->decrement('stok', 1);
                }
            }

            \DB::commit(); // Simpan semua perubahan

            return response()->json([
                'message' => 'Pesanan berhasil dibuat & stok diperbarui!',
                'invoice' => $transaction->invoice_number
            ]);
        } catch (\Exception $e) {
            \DB::rollback(); // Batalkan semua kalau ada error
            return response()->json(['message' => 'Waduh error: ' . $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($trx) {
                // Jika datanya masih string, kita decode jadi array
                if (is_string($trx->items_details)) {
                    $trx->items_details = json_decode($trx->items_details, true);
                }
                return $trx;
            });

        return view('transactions.index', compact('transactions'));
    }
}
