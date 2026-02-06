<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ItemShop;
use App\Models\SiteSetting;
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
    public function checkout(Request $request)
    {
        $this->authorize('membeli-produk');

        if (!Auth::check()) {
            return response()->json(['message' => 'Login dulu bolo!'], 401);
        }

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'total_price' => $request->total_price,
                'status' => 'pending',
                'items_details' => $request->items,
            ]);

            if (is_array($request->items)) {
                foreach ($request->items as $cartItem) {
                    $item = ItemShop::find($cartItem['id']);
                    if ($item) {
                        $item->decrement('stok', 1);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dibuat & stok diperbarui!',
                'invoice' => $transaction->invoice_number
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Waduh error: ' . $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        // Auto-fail expired pending transactions for this user
        $expired = Transaction::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(15))
            ->get();

        foreach ($expired as $t) {
            $t->failTransaction();
        }

        $query = Transaction::where('user_id', Auth::id())->with(['seller']);

        // Filter Status
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

        $transactions = $query->latest()->paginate(10);

        return view('shop.cart.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $user = Auth::user();

        // Check ownership or role access
        if ($transaction->user_id !== $user->id && 
            !$user->hasRole('admin') && 
            $transaction->seller_id !== $user->id) {
            abort(403, 'Akses ditolak bolo, ini bukan pesananmu!');
        }

        // Extra check: If they are admin/seller but NOT the seller for this specific transaction
        if ($user->hasRole('admin') || $user->hasRole('seller')) {
            // Kita ingin admin yang login sebagai seller HANYA bisa lihat order miliknya
            // Kecuali dia super-admin (asumsi role 'admin' di sistem ini adalah role seller tertinggi/platform owner)
            // Namun user minta: "jangan biarkan admin jg bisa melihat order toko lain"
            if ($transaction->seller_id !== $user->id && $transaction->user_id !== $user->id) {
                 // Check if it's strictly the platform owner
                 // If the platform design is everyone is a seller + admin cap, then we restrict.
                 abort(403, 'Anda tidak diizinkan melihat pesanan toko lain.');
            }
        }

        // Support Ajax for live status check
        if (request()->ajax()) {
            return response()->json([
                'status' => $transaction->status,
                'status_label' => strtoupper($transaction->status)
            ]);
        }

        // Check for expired Specifically on show
        if ($transaction->status === 'pending' && $transaction->created_at->addMinutes(15)->isPast()) {
            $transaction->failTransaction();
            $transaction->refresh(); // Sync the model after update
        }

        return view('shop.cart.show', compact('transaction'));
    }

    public function changePayment(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($transaction->status, ['pending', 'waiting_confirmation'])) {
            return back()->with('error', 'Transaksi sudah tidak bisa diubah bol!');
        }

        $method = $request->payment_method;
        
        DB::beginTransaction();
        try {
            if ($method === 'midtrans') {
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production', false);
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $items = $transaction->items_details;
                $serviceFee = (int) SiteSetting::where('key', 'admin_fee')->value('value') ?: 2000;

                $params = [
                    'transaction_details' => [
                        'order_id' => $transaction->invoice_number . '--' . time(),
                        'gross_amount' => (int) $transaction->total_price,
                    ],
                    'item_details' => array_merge(
                        collect($items)->map(fn($i) => [
                            'id' => $i['id'],
                            'price' => $i['harga'] ?? ($i['price'] ?? 0),
                            'quantity' => $i['quantity'] ?? 1,
                            'name' => substr($i['nama_barang'] ?? ($i['name'] ?? 'Item'), 0, 50),
                        ])->toArray(),
                        [[
                            'id' => 'SERVICE-FEE',
                            'price' => $serviceFee,
                            'quantity' => 1,
                            'name' => 'Biaya Layanan'
                        ]],
                        (float)$transaction->shipping_fee > 0 ? [[
                            'id' => 'SHIPPING-FEE',
                            'price' => (int) $transaction->shipping_fee,
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
                $transaction->update([
                    'payment_method' => 'midtrans',
                    'snap_token' => $snapToken,
                    'status' => 'pending'
                ]);
            } else {
                $transaction->update([
                    'payment_method' => 'cod',
                    'snap_token' => null,
                    'status' => 'waiting_confirmation'
                ]);
            }

            DB::commit();
            
            $msg = 'Metode pembayaran berhasil diubah bolo!';
            if ($request->payment_method === 'midtrans') {
                return back()->with('success', $msg)->with('auto_pay', $snapToken);
            }
            
            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal ganti pembayaran: ' . $e->getMessage());
        }
    }

    public function confirmReceipt(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($transaction->status, ['shipped', 'delivered'])) {
            return back()->with('error', 'Pesanan belum dikirim atau sudah selesai bolo!');
        }

        $transaction->completeTransaction();

        return back()->with('success', 'MANTAP BOLO! Pesanan berhasil diterima.');
    }
}
