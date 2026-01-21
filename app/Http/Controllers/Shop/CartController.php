<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ItemShop;

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

    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($trx) {
                if (is_string($trx->items_details)) {
                    $trx->items_details = json_decode($trx->items_details, true);
                }
                return $trx;
            });

        return view('shop.cart.index', compact('transactions'));
    }
}
