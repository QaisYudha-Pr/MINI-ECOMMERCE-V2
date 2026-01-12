<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ItemShop;

class ReviewController extends Controller
{
    public function store(Request $request, ItemShop $itemShop)
    {
        $this->authorize('create', Review::class);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Rating harus dipilih',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'comment.required' => 'Komentar tidak boleh kosong',
            'comment.min' => 'Komentar minimal 10 karakter',
            'comment.max' => 'Komentar maksimal 1000 karakter',
        ]);

        Review::create([
            'item_shop_id' => $itemShop->id,
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Review berhasil ditambahkan!');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $itemShopId = $review->item_shop_id;
        $review->delete();

        return back()->with('success', 'Review berhasil dihapus!');
    }
}
