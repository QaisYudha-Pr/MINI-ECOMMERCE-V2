<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemShop;

class HomeController extends Controller
{
    public function index()
    {
        // 8 Latest Items
        $items = ItemShop::where('stok', '>', 0)
            ->withCount('reviews')
            ->withAvg('reviews as ratings_avg', 'rating')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        // Trusted Sellers (Ide 3)
        $trustedSellers = \App\Models\User::role('seller')
            ->withCount('itemShops')
            ->whereHas('itemShops')
            ->latest()
            ->take(6)
            ->get();

        return view('shop.home', compact('items', 'trustedSellers'));
    }
}
