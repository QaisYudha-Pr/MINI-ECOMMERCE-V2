<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use Illuminate\Http\Request;

class ItemShopController extends Controller
{
    public function public(Request $request)
    {
        $query = ItemShop::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category') && !in_array($request->category, ['all', 'Semua'])) {
            $query->where('kategori', $request->category);
        }

        $items = $query->withCount('reviews')
            ->withAvg('reviews as ratings_avg', 'rating')
            ->latest()
            ->paginate(20)
            ->withQueryString();
        $categories = ItemShop::distinct()->pluck('kategori')->filter()->values();

        return view('shop.index', compact('items', 'categories'));
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
