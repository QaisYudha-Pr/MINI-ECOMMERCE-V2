<?php

namespace App\Http\Controllers\Shop\User;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
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
