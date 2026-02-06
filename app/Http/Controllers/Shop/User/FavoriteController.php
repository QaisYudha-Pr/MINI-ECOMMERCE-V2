<?php

namespace App\Http\Controllers\Shop\User;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->favoriteItems();

        // Proximity Logic
        if ($user->latitude && $user->longitude) {
            $userLat = $user->latitude;
            $userLon = $user->longitude;
            
            $query->leftJoin('users as sellers', 'item_shops.user_id', '=', 'sellers.id')
                ->select('item_shops.*')
                ->addSelect(DB::raw("(6371 * acos(cos(radians($userLat)) * cos(radians(sellers.latitude)) * cos(radians(sellers.longitude) - radians($userLon)) + sin(radians($userLat)) * sin(radians(sellers.latitude)))) AS distance"));
        }

        // Category Filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('kategori', $request->category);
        }

        $favorites = $query->with('user')->get();

        // Get categories present in current user's favorites
        $categories = $user->favoriteItems()->distinct()->pluck('kategori')->filter();

        return view('shop.wishlist', compact('favorites', 'categories'));
    }

    public function toggle(ItemShop $item)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $isFavorited = $user->favoriteItems()->where('item_shop_id', $item->id)->exists();

        if ($isFavorited) {
            $user->favoriteItems()->detach($item->id);
            $status = false;
        } else {
            $user->favoriteItems()->attach($item->id);
            $status = true;
        }

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Ditambahkan ke favorit' : 'Dihapus dari favorit'
        ]);
    }
}
