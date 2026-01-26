<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Midtrans Data:', $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $request->order_id;
        $invoiceNumber = $orderId;
        
        // Cek apakah ada suffix timestamp (digunakan saat ganti pembayaran)
        if (str_contains($orderId, '--')) {
            $parts = explode('--', $orderId);
            $invoiceNumber = $parts[0];
        }

        $transaction = Transaction::where('invoice_number', $invoiceNumber)->first();

        if (!$transaction) {
            if (str_contains($request->order_id, 'payment_notif_test')) {
                return response()->json(['message' => 'Test notification success'], 200);
            }
            
            Log::error('Transaksi tidak ada di DB: ' . $request->order_id);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // JIKA TRANSAKSI SUDAH SUKSES, JANGAN DIUBAH LAGI (Mencegah notifikasi expired dari token lama menimpa status sukses)
        if ($transaction->status === 'success') {
            return response()->json(['message' => 'Transaction already success'], 200);
        }

        $status = $request->transaction_status;

        if ($status == 'capture' || $status == 'settlement') {
            $transaction->update(['status' => 'success']);
        } elseif ($status == 'pending') {
            $transaction->update(['status' => 'pending']);
        } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
            $transaction->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'OK']);
    }
}
