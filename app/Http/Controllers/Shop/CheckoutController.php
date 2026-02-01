<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use App\Models\Transaction;
use App\Models\SiteSetting;
use App\Models\Notification;
use App\Models\User;
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
            'admin_fee',
            'free_shipping_min_order',
            'free_shipping_max_dist',
            'free_shipping_limit_dist',
            'free_shipping_subsidy'
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
            
            $settings = SiteSetting::whereIn('key', ['admin_fee'])->pluck('value', 'key');
            $biayaLayananGlobal = (int)($settings['admin_fee'] ?? 2000);

            // 1. Kelompokkan item berdasarkan Seller
            $itemsBySeller = [];
            foreach ($request->cart as $cartItem) {
                $item = ItemShop::with('user')->where('id', $cartItem['id'])->lockForUpdate()->first();
                if (!$item) throw new Exception("Barang {$cartItem['nama_barang']} sudah tidak tersedia!");
                
                $qty = $cartItem['quantity'] ?? 1;
                if ($item->stok < $qty) throw new Exception("Stok {$item->nama_barang} sisa {$item->stok}!");

                $itemTotal = $item->harga * $qty;
                $item->decrement('stok', $qty);

                $sellerId = $item->user_id;
                $itemsBySeller[$sellerId][] = [
                    'id' => $item->id,
                    'seller_id' => $sellerId,
                    'seller_name' => $item->user->nama_toko ?? $item->user->name,
                    'nama_barang' => $item->nama_barang,
                    'gambar' => $item->gambar,
                    'harga' => (int)$item->harga,
                    'berat' => (int)$item->berat,
                    'quantity' => (int)$qty,
                    'total' => (int)$itemTotal
                ];
                $subtotal += $itemTotal;
            }

            // 2. Buat Transaksi Terpisah per Seller
            $parentInvoice = 'INV-' . date('Ymd') . strtoupper(Str::random(6));
            $createdTransactions = [];
            $totalBayarAkhir = 0;
            
            $totalShipping = (int)$request->shipping_fee;
            $sellerCount = count($itemsBySeller);
            $shippingPerSeller = floor($totalShipping / $sellerCount);
            $serviceFeePerSeller = floor($biayaLayananGlobal / $sellerCount);

            reset($itemsBySeller);
            $firstSellerId = key($itemsBySeller);

            foreach ($itemsBySeller as $sellerId => $items) {
                $sellerSubtotal = collect($items)->sum('total');
                
                $currentShipping = $shippingPerSeller;
                $currentAdminFee = $serviceFeePerSeller;
                
                if ($sellerId === $firstSellerId) {
                    $currentShipping += ($totalShipping % $sellerCount);
                    $currentAdminFee += ($biayaLayananGlobal % $sellerCount);
                }

                $trxTotal = $sellerSubtotal + $currentShipping + $currentAdminFee;
                $totalBayarAkhir += $trxTotal;

                $transaction = Transaction::create([
                    'user_id' => auth()->id(),
                    'seller_id' => $sellerId,
                    'invoice_number' => $parentInvoice . '-' . $sellerId,
                    'parent_invoice' => $parentInvoice,
                    'total_price' => $trxTotal,
                    'shipping_fee' => $currentShipping,
                    'admin_fee' => $currentAdminFee,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'alamat' => $request->alamat,
                    'items_details' => $items,
                    'courier_name' => $request->courier_name,
                    'courier_service' => $request->courier_service,
                    'courier_service_id' => $request->courier_service_id,
                    'destination_area_id' => $request->destination_area_id,
                ]);

                $createdTransactions[] = $transaction;

                // Notif Seller
                Notification::create([
                    'user_id' => $sellerId,
                    'title' => 'Pesanan Baru 📦',
                    'message' => "Ada pesanan baru #{$transaction->invoice_number} bolo, segera cek!",
                    'type' => 'info'
                ]);
            }

            // Notif Admin
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Transaksi Baru 🛒',
                    'message' => "Ada transaksi baru {$parentInvoice} di platform bolo!",
                    'type' => 'info'
                ]);
            }

            $snapToken = null;

            if ($request->payment_method === 'midtrans') {
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production', false);
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => $parentInvoice,
                        'gross_amount' => (int)$totalBayarAkhir,
                    ],
                    'customer_details' => [
                        'first_name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                foreach ($createdTransactions as $t) {
                    $t->update(['snap_token' => $snapToken]);
                }
            } else {
                foreach ($createdTransactions as $t) {
                    $t->update(['status' => 'waiting_confirmation']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'invoice' => $parentInvoice,
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
