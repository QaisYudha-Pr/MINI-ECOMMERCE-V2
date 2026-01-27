<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use App\Models\Transaction;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class CheckoutController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::whereIn('key', [
            'shipping_base_fee',
            'shipping_per_km',
            'shipping_per_kg',
            'service_fee'
        ])->pluck('value', 'key');

        return view('shop.checkout.index', [
            'settings' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('membeli-produk');
        try {
            DB::beginTransaction();

            $subtotal = 0;
            $itemsSummary = [];
            
            $settings = SiteSetting::whereIn('key', ['service_fee'])->pluck('value', 'key');
            $biayaLayanan = (int)($settings['service_fee'] ?? 2500);

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
                    'kategori' => $item->kategori,
                    'harga' => (int)$item->harga,
                    'berat' => (int)$item->berat,
                    'quantity' => (int)$qty,
                    'total' => (int)$itemTotal
                ];
            }

            $grandTotal = $subtotal + $biayaLayanan + (int)$request->shipping_fee;

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . date('Ymd') . strtoupper(Str::random(6)),
                'total_price' => $grandTotal,
                'shipping_fee' => (int)$request->shipping_fee,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'alamat' => $request->alamat,
                'items_details' => $itemsSummary,                'courier_name' => $request->courier_name,
                'courier_service' => $request->courier_service,
                'destination_area_id' => $request->destination_area_id,            ]);

            $snapToken = null;

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
                        ]],
                        (int)$request->shipping_fee > 0 ? [[
                            'id' => 'SHIPPING-FEE',
                            'price' => (int)$request->shipping_fee,
                            'quantity' => 1,
                            'name' => 'Biaya Pengiriman'
                        ]] : []
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
