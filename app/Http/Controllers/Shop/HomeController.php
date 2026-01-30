<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemShop;

class HomeController extends Controller
{
    public function index()
    {
        $settings = \App\Models\SiteSetting::all()->pluck('value', 'key');
        
        // 8 Latest Items
        $items = ItemShop::where('stok', '>', 0)
            ->withCount('reviews')
            ->withAvg('reviews as ratings_avg', 'rating')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        // Trusted Sellers (Pilihan Bolo: Berdasarkan rating rata-rata produk dan jumlah produk)
        $trustedSellers = \App\Models\User::role('seller')
            ->withCount('itemShops')
            ->whereHas('itemShops')
            ->withAvg('itemShops as avg_rating', 'stok') // Placeholder, idealnya pake tabel review
            ->orderByDesc('item_shops_count')
            ->take(6)
            ->get();

        return view('shop.home', compact('items', 'trustedSellers', 'settings'));
    }
}
