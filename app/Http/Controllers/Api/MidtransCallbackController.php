<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction; 
use App\Models\Notification;
use App\Models\User;
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

        // Cari transaksi berdasarkan invoice_number (Parent Invoice) atau child invoice
        $transactions = Transaction::where('invoice_number', $invoiceNumber)
            ->orWhere('parent_invoice', $invoiceNumber)
            ->get();

        if ($transactions->isEmpty()) {
            if (str_contains($request->order_id, 'payment_notif_test')) {
                return response()->json(['message' => 'Test notification success'], 200);
            }
            
            Log::error('Transaksi tidak ada di DB: ' . $request->order_id);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $firstTransaction = $transactions->first();

        // JIKA TRANSAKSI SUDAH SUKSES, JANGAN DIUBAH LAGI
        if ($firstTransaction->status === 'success') {
            return response()->json(['message' => 'Transaction already success'], 200);
        }

        $status = $request->transaction_status;
        $dbStatus = 'pending';

        if ($status == 'capture' || $status == 'settlement') {
            $dbStatus = 'success';
        } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
            $dbStatus = 'failed';
        }

        // Update status SEMUA transaksi dengan parent_invoice yang sama (Transaction Splitting support)
        foreach ($transactions as $transaction) {
            if ($dbStatus === 'failed') {
                $transaction->failTransaction();
            } else {
                $transaction->update(['status' => $dbStatus]);
            }

            // Jika sukses, kirim notifikasi ke Seller, Admin, dan User
            if ($dbStatus === 'success') {
                // Notif ke User Pembeli
                Notification::create([
                    'user_id' => $transaction->user_id,
                    'title' => 'Pembayaran Berhasil! ✅',
                    'message' => "Hore bolo! Pembayaran untuk #{$transaction->invoice_number} sudah kami terima. Pesananmu akan segera diproses seller.",
                    'type' => 'success'
                ]);

                // Notif ke Seller
                if ($transaction->seller_id) {
                    Notification::create([
                        'user_id' => $transaction->seller_id,
                        'title' => 'Order Masuk 📦',
                        'message' => "Pesanan baru #{$transaction->invoice_number} telah dibayar.",
                        'type' => 'success'
                    ]);
                }

                // Notif ke Admin
                $admins = User::role('admin')->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title' => 'Transaksi Baru 💸',
                        'message' => "Pembayaran diterima untuk #{$transaction->invoice_number}.",
                        'type' => 'success'
                    ]);
                }
            }
        }

        return response()->json(['message' => 'OK']);
    }
}
