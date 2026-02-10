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
            $itemImage = $item->gambar ?? null;
            
            // Get initials for fallback
            $initials = collect(explode(' ', $item->nama_barang))
                ->map(fn($n) => mb_substr($n, 0, 1))
                ->take(1)
                ->join('');

            return [
                'id' => $item->id,
                'name' => $item->nama_barang,
                'location' => $item->lokasi ?? 'Mojokerto',
                'seller_name' => $item->user->nama_toko ?? $item->user->name ?? 'Official Store',
                'image' => $itemImage ? (str_starts_with($itemImage, 'http') ? $itemImage : asset($itemImage)) : null,
                'initials' => $initials,
                'url' => route('shop.show', $item->id),
                'is_verified' => (bool)$item->user->is_top_seller
            ];
        });

        return response()->json($results);
    }
}
