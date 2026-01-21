<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use Illuminate\Http\Request;

class ItemShopController extends Controller
{
    public function public()
    {
        $items = ItemShop::latest()->paginate(8);
        return view('shop.index', compact('items'));
    }

    public function show(ItemShop $itemShop)
    {
        $reviews = $itemShop->reviews()->latest()->get();
        return view('shop.show', compact('itemShop', 'reviews'));
    }

    public function stats()
    {
        return response()->json(ItemShop::select('id', 'total_terjual', 'stok')->get());
    }
}
