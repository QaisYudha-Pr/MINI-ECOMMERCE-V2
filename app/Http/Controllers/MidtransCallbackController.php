<?php

namespace App\Http\Controllers;

use App\Models\Transaction; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function callback(Request $request)
    {
        // Catat data masuk untuk debugging
        Log::info('Midtrans Data:', $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // Verifikasi Signature
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari transaksi (Ganti 'invoice_number' sesuai nama kolom di DB-mu)
        $transaction = Transaction::where('invoice_number', $request->order_id)->first();

        if (!$transaction) {
            // Jika ini hanya testing dari tombol dashboard Midtrans
            if (str_contains($request->order_id, 'payment_notif_test')) {
                return response()->json(['message' => 'Test notification success'], 200);
            }
            
            Log::error('Transaksi tidak ada di DB: ' . $request->order_id);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update status berdasarkan respon Midtrans
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