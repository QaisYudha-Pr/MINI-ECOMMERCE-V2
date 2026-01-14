<?php

namespace App\Http\Controllers;

use App\Models\ItemShop;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. Validasi & Hitung Total
            $totalPrice = 0;
            $itemsSummary = [];
            foreach ($request->cart as $cartItem) {
                // Gunakan nama kolom yang sesuai di tabel items kamu (id, harga, nama_barang)
                $item = ItemShop::findOrFail($cartItem['id']);
                
                if ($item->stok < 1) {
                    throw new Exception("Stok {$item->nama_barang} habis!");
                }

                $totalPrice += $item->harga;
                $item->decrement('stok');

                $itemsSummary[] = [
                    'id' => $item->id,
                    'name' => $item->nama_barang,
                    'price' => (int)$item->harga,
                    'quantity' => 1
                ];
            }

            // 2. Simpan ke Database
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'alamat' => $request->alamat,
                'items_details' => $itemsSummary,
            ]);

            $snapToken = null;

            // 3. Logic Midtrans
            if ($request->payment_method === 'midtrans') {
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production', false);
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => $transaction->invoice_number,
                        'gross_amount' => (int)$totalPrice,
                    ],
                    'customer_details' => [
                        'first_name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                    ],
                    'item_details' => $itemsSummary
                ];

                $snapToken = Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'invoice' => $transaction->invoice_number
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}