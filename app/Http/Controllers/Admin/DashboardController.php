<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use App\Models\User;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // Handle Export Request
        if ($request->has('export') && $request->get('export') === 'excel') {
            return Excel::download(new TransactionExport, 'transactions_report_' . date('Y-m-d') . '.xlsx');
        }

        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');

        // 1. Basic Stats
        $totalItems = ItemShop::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))->count();
        $totalUsers = User::count();
        $totalReviews = Review::when(!$isAdmin, function($q) use ($user) {
            $q->whereHas('itemShop', fn($iq) => $iq->where('user_id', $user->id));
        })->count();
        
        // 2. Fetch all successful transactions for detailed analysis
        $transactions = Transaction::where('status', 'success')->get();

        // 3. Category Filter Logic
        $selectedCategory = $request->get('category', 'all');
        $categories = ItemShop::distinct()->pluck('kategori')->filter();

        // 4. Detailed Metrics Logic (Revenue by Item/Category & Stats Cleanup)
        $itemStats = [];
        $totalEarnings = 0;
        $totalOrdersCount = 0;

        foreach ($transactions as $transaction) {
            $items = $transaction->items_details;
            if (!is_array($items)) continue;

            $hasOwnedItem = false;
            foreach ($items as $item) {
                $isOwnItem = false;
                if ($isAdmin) {
                    $isOwnItem = true;
                } else {
                    // Match by 'id' from checkout item_details
                    $itemId = $item['id'] ?? null;
                    if ($itemId) {
                        $ownedProduct = ItemShop::where('id', $itemId)->where('user_id', $user->id)->first();
                        if ($ownedProduct) $isOwnItem = true;
                    }
                }

                if ($isOwnItem) {
                    $hasOwnedItem = true;
                    $itemName = $item['nama_barang'] ?? ($item['name'] ?? 'Unknown Item');
                    $itemCategory = $item['kategori'] ?? 'Uncategorized';
                    $itemPrice = (int)($item['harga'] ?? ($item['price'] ?? 0));
                    $itemQty = (int)($item['quantity'] ?? 1);

                    // Add to total earnings (Role based)
                    $totalEarnings += ($itemPrice * $itemQty);

                    // Filter by category for the chart/list
                    if ($selectedCategory !== 'all' && strtolower($itemCategory) !== strtolower($selectedCategory)) {
                        continue;
                    }

                    if (!isset($itemStats[$itemName])) {
                        $itemStats[$itemName] = [
                            'name' => $itemName,
                            'kategori' => $itemCategory,
                            'total' => 0,
                        ];
                    }
                    $itemStats[$itemName]['total'] += ($itemPrice * $itemQty);
                }
            }
            if ($isAdmin || $hasOwnedItem) {
                $totalOrdersCount++;
            }
        }

        $revenueByOffer = collect($itemStats)->sortByDesc('total')->values()->all();
        $maxRevenue = collect($revenueByOffer)->max('total') ?: 1;

        // 5. Revenue Timeline (7 Days)
        $revenueTimeline = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            
            $dayTotal = 0;
            foreach ($transactions as $transaction) {
                if ($transaction->created_at->format('Y-m-d') !== $date) continue;
                
                // Aggregate items based on category filter
                $items = $transaction->items_details;
                if (!is_array($items)) continue;

                foreach ($items as $item) {
                    if (!$isAdmin) {
                        $itemId = $item['id'] ?? null;
                        if (!$itemId) continue;
                        $ownedProduct = ItemShop::where('id', $itemId)->where('user_id', $user->id)->first();
                        if (!$ownedProduct) continue;
                    }

                    $itemCategory = $item['kategori'] ?? 'Uncategorized';
                    if ($selectedCategory !== 'all' && strtolower($itemCategory) !== strtolower($selectedCategory)) {
                        continue;
                    }

                    $itemPrice = (int)($item['harga'] ?? ($item['price'] ?? 0));
                    $itemQty = (int)($item['quantity'] ?? 1);
                    $dayTotal += ($itemPrice * $itemQty);
                }
            }

            $revenueTimeline->push([
                'date' => Carbon::parse($date)->format('d M'),
                'total' => $dayTotal
            ]);
        }

        // 6. Revenue by User (Admin Only)
        $revenueByUser = [];
        if ($isAdmin) {
            $userStats = [];
            foreach ($transactions as $transaction) {
                $userName = $transaction->user->name ?? 'Guest';
                $userEmail = $transaction->user->email ?? '-';
                $userAvatar = $transaction->user->avatar ?? null;
                $userId = $transaction->user_id;

                if (!isset($userStats[$userId])) {
                    $userStats[$userId] = [
                        'name' => $userName,
                        'email' => $userEmail,
                        'avatar' => $userAvatar,
                        'total' => 0
                    ];
                }
                $userStats[$userId]['total'] += $transaction->total_price;
            }
            $revenueByUser = collect($userStats)->sortByDesc('total')->take(5)->all();
        }

        // 7. AJAX Response
        if ($request->ajax()) {
            return response()->json([
                'revenueByOffer' => $revenueByOffer,
                'maxRevenue' => $maxRevenue,
                'revenueTimeline' => $revenueTimeline
            ]);
        }

        $bestSellers = ItemShop::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalItems',
            'totalUsers',
            'totalReviews',
            'totalEarnings',
            'totalOrdersCount',
            'bestSellers',
            'categories',
            'selectedCategory',
            'revenueByOffer',
            'maxRevenue',
            'revenueByUser',
            'revenueTimeline'
        ));
    }
}
