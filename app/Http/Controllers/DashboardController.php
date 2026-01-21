<?php

namespace App\Http\Controllers;

use App\Models\ItemShop;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $selectedCategory = $request->get('category', 'all');

        if ($user->hasRole('admin')) {
            // Get All Available Categories for Filter
            $categories = ItemShop::select('kategori')->distinct()->get()->pluck('kategori')->filter();

            // Gross Revenue
            $totalEarnings = \App\Models\Transaction::where('status', 'success')->sum('total_price');
            
            // Revenue Timeline (Last 7 days)
            $timeline = \App\Models\Transaction::where('status', 'success')
                ->where('created_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
                ->groupBy('date')
                ->get();

            // Revenue by Product (Top 5)
            $revenueQuery = \App\Models\Transaction::where('status', 'success')->get();
            
            $revenueByOffer = $revenueQuery
                ->flatMap(function ($t) {
                    // Pastikan items_details adalah array sebelum di-flatten
                    return is_array($t->items_details) ? $t->items_details : [];
                })
                ->map(function($item) {
                    if (empty($item['kategori'])) {
                        $actualItem = ItemShop::find($item['id'] ?? 0);
                        $item['kategori'] = $actualItem ? $actualItem->kategori : null;
                    }
                    return $item;
                })
                ->filter(function ($item) use ($selectedCategory) {
                    if ($selectedCategory === 'all') return true;
                    return strtolower($item['kategori'] ?? '') === strtolower($selectedCategory);
                })
                ->groupBy('nama_barang')
                ->map(function ($items) {
                    $total = collect($items)->sum('total');
                    $kat = $items[0]['kategori'] ?? null;
                    // Hilangkan label jika kategori bertuliskan "umum" (case insensitive)
                    if ($kat && strtolower($kat) === 'umum') $kat = null;
                    
                    return [
                        'total'    => $total,
                        'kategori' => $kat,
                    ];
                })
                ->filter(fn($item) => $item['total'] > 0)
                ->sortByDesc('total')
                ->take(5);

            // Revenue by Customer (Top 5)
            $revenueByCustomer = \App\Models\Transaction::where('status', 'success')
                ->with('user')
                ->selectRaw('user_id, SUM(total_price) as total')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->take(5)
                ->get();

            return view('dashboard', [
                'totalItem' => ItemShop::count(),
                'totalUser' => User::count(),
                'totalEarnings' => $totalEarnings,
                'revenueTimeline' => $timeline,
                'revenueByOffer' => $revenueByOffer,
                'maxRevenue' => $revenueByOffer->max('total') ?: 1, // Untuk skala progress bar
                'revenueByCustomer' => $revenueByCustomer,
                'categories' => $categories,
                'selectedCategory' => $selectedCategory,
            ]);
        }

        return view('dashboard', [
            'totalItem' => $user->itemShops()->count(),
            'totalEarnings' => 0,
            'revenueTimeline' => collect(),
            'revenueByOffer' => collect(),
            'revenueByCustomer' => collect(),
        ]);
    }
}

