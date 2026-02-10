<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemShopController extends Controller
{
    public function public(Request $request)
    {
        $query = ItemShop::query()->select('item_shops.*');
        $seller = null;

        // Proximity Logic
        if (Auth::check() && Auth::user()->latitude && Auth::user()->longitude) {
            $userLat = Auth::user()->latitude;
            $userLon = Auth::user()->longitude;
            
            $query->leftJoin('users', 'item_shops.user_id', '=', 'users.id')
                ->addSelect(DB::raw("(6371 * acos(cos(radians($userLat)) * cos(radians(users.latitude)) * cos(radians(users.longitude) - radians($userLon)) + sin(radians($userLat)) * sin(radians(users.latitude)))) AS distance"));
        }

        if ($request->filled('seller_id')) {
            $seller = \App\Models\User::find($request->seller_id);
            if ($seller) {
                $query->where('user_id', $seller->id);
                
                // Load stats for Profile Toko
                $seller->loadCount('itemShops');
                $seller->total_sold = $seller->itemShops()->sum('total_terjual');
                
                // Get Detailed Rating Breakdown
                $itemIds = $seller->itemShops()->pluck('id');
                $productRatingData = \App\Models\Review::whereIn('item_shop_id', $itemIds);
                $sellerRatingData = \App\Models\SellerReview::where('seller_id', $seller->id);

                $seller->product_avg = $productRatingData->avg('rating') ?: 0;
                $seller->product_count = $productRatingData->count();
                
                $seller->service_avg = $sellerRatingData->avg('rating') ?: 0;
                $seller->service_count = $sellerRatingData->count();

                // Unified average for compatibility
                $seller->avg_rating = $seller->seller_rating ?? 0;
                $seller->total_reviews = $seller->seller_rating_count ?? 0;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('nama_toko', 'like', '%' . $search . '%')
                         ->orWhere('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('seller_id')) {
            $query->where('user_id', $request->seller_id);
        }

        if ($request->filled('category') && !in_array($request->category, ['all', 'Semua'])) {
            $query->where('kategori', $request->category);
        }

        // Hide out of stock items
        $query->where('stok', '>', 0);

        if ($request->filled('sort') && $request->sort === 'nearest' && Auth::check() && Auth::user()->latitude) {
            $query->orderBy('distance', 'asc');
        } else {
            $query->latest();
        }

        $items = $query->with('user:id,name,nama_toko,latitude,longitude,seller_rating,seller_rating_count,is_top_seller')
            ->withCount('reviews')
            ->withAvg('reviews as ratings_avg', 'rating')
            ->paginate(20)
            ->withQueryString();
        
        $categories = ItemShop::distinct()->pluck('kategori')->filter()->values();
        
        $sellerReviews = collect();
        if ($seller) {
            // Get combined or specific seller reviews
            // User requested that reviews from transaction "masuk ke toko"
            $sellerReviews = \App\Models\SellerReview::where('seller_id', $seller->id)
                ->with('buyer')
                ->latest()
                ->take(15)
                ->get();
                
            // If empty, maybe show some product reviews as fallback
            if ($sellerReviews->isEmpty()) {
                $itemIds = $seller->itemShops()->pluck('id');
                $sellerReviews = \App\Models\Review::whereIn('item_shop_id', $itemIds)
                    ->with('user', 'itemShop')
                    ->latest()
                    ->take(10)
                    ->get();
            }
        }

        $isFollowing = false;
        if (auth()->check() && $seller) {
            $isFollowing = auth()->user()->isFollowing($seller->id);
        }

        return view('shop.index', compact('items', 'categories', 'seller', 'sellerReviews', 'isFollowing'));
    }

    public function show(ItemShop $itemShop)
    {
        $reviews = $itemShop->reviews()->latest()->get();
        
        // Related products: priority same category, then same seller
        // We use shuffle and take to keep it interesting but relevant
        $relatedItems = ItemShop::with(['user:id,name,nama_toko,latitude,longitude,seller_rating,seller_rating_count,is_top_seller'])
            ->withAvg('reviews', 'rating')
            ->where('id', '!=', $itemShop->id)
            ->where(function($q) use ($itemShop) {
                $q->where('kategori', $itemShop->kategori)
                  ->orWhere('user_id', $itemShop->user_id);
            })
            ->orderByRaw("CASE WHEN kategori = ? THEN 0 ELSE 1 END", [$itemShop->kategori])
            ->latest()
            ->take(10)
            ->get()
            ->shuffle()
            ->take(6);

        return view('shop.show', compact('itemShop', 'reviews', 'relatedItems'));
    }

    public function stats()
    {
        return response()->json(ItemShop::select('id', 'total_terjual', 'stok')->get());
    }
}
