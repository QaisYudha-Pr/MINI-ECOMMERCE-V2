<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        $lowerQ = strtolower($q);
        // Pecah kata kunci (misal: "mini proyektor" -> ["mini", "proyektor"])
        $keywords = preg_split('/\s+/', $lowerQ, -1, PREG_SPLIT_NO_EMPTY);

        $items = ItemShop::query()
            ->with(['user'])
            ->where('stok', '>', 0)
            ->where(function($query) use ($lowerQ, $keywords) {
                // 1. Cek frase utuh dulu (Prioritas tinggi)
                $query->whereRaw('LOWER(nama_barang) LIKE ?', ["%{$lowerQ}%"])
                      ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$lowerQ}%"]);
                
                // 2. Cek per kata kunci
                foreach ($keywords as $word) {
                    $query->orWhere(function($sub) use ($word) {
                        $sub->whereRaw('LOWER(nama_barang) LIKE ?', ["%{$word}%"])
                            ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$word}%"])
                            ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$word}%"]);
                    });
                }
            })
            // Tambahkan sedikit bobot: Nama barang yang mirip ditaruh di atas
            ->orderByRaw("CASE WHEN LOWER(nama_barang) LIKE ? THEN 1 ELSE 2 END", ["%{$lowerQ}%"])
            ->latest()
            ->limit(10)
            ->get();

        $results = $items->map(function($item) {
            $avatar = $item->user->avatar ?? null;
            $sellerAvatar = $avatar 
                ? (str_starts_with($avatar, 'http') ? $avatar : asset($avatar))
                : 'https://ui-avatars.com/api/?name=' . urlencode($item->user->nama_toko ?? $item->user->name ?? 'OS') . '&color=00AA5B&background=D1FAE5';

            return [
                'id' => $item->id,
                'name' => $item->nama_barang,
                'location' => $item->lokasi ?? 'Mojokerto',
                'seller_name' => $item->user->nama_toko ?? $item->user->name ?? 'Official Store',
                'seller_avatar' => $sellerAvatar,
                'url' => route('shop.show', $item->id),
                'is_verified' => true
            ];
        });

        return response()->json($results);
    }
}
