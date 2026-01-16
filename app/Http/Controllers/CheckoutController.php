<?php

namespace App\Http\Controllers;

use App\Models\ItemShop;
use App\Models\Transaction;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Import dipisah agar lebih stabil
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $subtotal = 0;
            $itemsSummary = [];
            $biayaLayanan = 2500;

            // 1. VALIDASI & PROSES STOK
            foreach ($request->cart as $cartItem) {
                $item = ItemShop::where('id', $cartItem['id'])->lockForUpdate()->first();

                if (!$item) {
                    throw new Exception("Barang {$cartItem['nama_barang']} sudah tidak tersedia!");
                }

                $qty = $cartItem['quantity'] ?? 1;

                if ($item->stok < $qty) {
                    throw new Exception("Stok {$item->nama_barang} sisa {$item->stok}, kamu pesan {$qty} bolo!");
                }

                $itemTotal = $item->harga * $qty;
                $subtotal += $itemTotal;
                
                $item->decrement('stok', $qty);

                $itemsSummary[] = [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'harga' => (int)$item->harga,
                    'quantity' => (int)$qty,
                    'total' => (int)$itemTotal
                ];
            }

            $grandTotal = $subtotal + $biayaLayanan;

            // 2. BUAT TRANSAKSI
            // Di sini line 58 yang tadi error, sekarang sudah pakai Str yang benar
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . date('Ymd') . strtoupper(Str::random(6)),
                'total_price' => $grandTotal,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'alamat' => $request->alamat,
                'items_details' => $itemsSummary,
            ]);

            $snapToken = null;

            // 3. LOGIC PEMBAYARAN
            if ($request->payment_method === 'midtrans') {
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production', false);
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => $transaction->invoice_number,
                        'gross_amount' => (int)$grandTotal,
                    ],
                    'item_details' => array_merge(
                        collect($itemsSummary)->map(fn($i) => [
                            'id' => $i['id'],
                            'price' => $i['harga'],
                            'quantity' => $i['quantity'],
                            'name' => substr($i['nama_barang'], 0, 50),
                        ])->toArray(),
                        [[
                            'id' => 'SERVICE-FEE',
                            'price' => $biayaLayanan,
                            'quantity' => 1,
                            'name' => 'Biaya Layanan'
                        ]]
                    ),
                    'customer_details' => [
                        'first_name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);
            } else {
                $transaction->update(['status' => 'waiting_confirmation']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'invoice' => $transaction->invoice_number,
                'payment_method' => $request->payment_method
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 400);
        }
    }
}