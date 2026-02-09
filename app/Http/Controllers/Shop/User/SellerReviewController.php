<?php

namespace App\Http\Controllers\Shop\User;

use App\Http\Controllers\Controller;
use App\Models\SellerReview;
use App\Models\Transaction;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerReviewController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $user = Auth::user();

        // Verify transaction belongs to user
        if ($transaction->user_id !== $user->id) {
            abort(403);
        }

        // Verify transaction is completed
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'Transaksi belum selesai bolo!');
        }

        // Check if already reviewed
        if (SellerReview::where('transaction_id', $transaction->id)->where('buyer_id', $user->id)->exists()) {
            return back()->with('error', 'Kamu sudah memberikan rating untuk seller ini!');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Get seller from transaction
        $sellerId = $transaction->seller_id;

        if (!$sellerId) {
            return back()->with('error', 'Tidak dapat menemukan seller!');
        }

        SellerReview::create([
            'seller_id' => $sellerId,
            'buyer_id' => $user->id,
            'transaction_id' => $transaction->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Notify seller
        NotificationService::send(
            $sellerId,
            'Rating Baru!',
            "{$user->name} memberikan rating {$validated['rating']} bintang untuk kamu bolo!",
            \App\Models\Notification::CATEGORY_TRANSACTION,
            $validated['rating'] >= 4 ? \App\Models\Notification::TYPE_SUCCESS : \App\Models\Notification::TYPE_INFO
        );

        return back()->with('success', 'Terima kasih atas rating-nya bolo!');
    }
}
