<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemShop;

class HomeController extends Controller
{
    public function index()
    {
        $items = ItemShop::where('stok', '>', 0)
            ->withCount('reviews')
            ->withAvg('reviews as ratings_avg', 'rating')
            ->latest()
            ->get();

        return view('shop.home', compact('items'));
    }
}
