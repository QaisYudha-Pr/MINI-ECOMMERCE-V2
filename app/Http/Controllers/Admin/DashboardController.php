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
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        // Redirect Courier to their specific page
        if ($user->hasRole('courier')) {
            return redirect()->route('courier.deliveries.index');
        }

        // Handle Export Request
        if ($request->has('export') && $request->get('export') === 'excel') {
            return Excel::download(new TransactionExport, 'transactions_report_' . date('Y-m-d') . '.xlsx');
        }

        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $isSeller = $user->hasRole('seller');

        // 1. Basic Stats (Admin/Seller)
        $totalItems = ItemShop::when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))->count();
        $totalUsers = User::count();
        $totalReviews = Review::when(!$isAdmin, function($q) use ($user) {
            $q->whereHas('itemShop', fn($iq) => $iq->where('user_id', $user->id));
        })->count();

        // 2. User Specific Stats (Regular User)
        // Only count legitimate orders (Paid, Shipped, Completed, or Waiting Confirmation)
        $userTransactionsCount = Transaction::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'success', 'shipped', 'completed', 'waiting_confirmation'])
            ->count();
        $userFavoritesCount = $user->favoriteItems()->count();
        $userReviewsCount = Review::where('user_id', $user->id)->count();
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        $platformBalance = $isAdmin ? $user->platform_balance : 0;
        
        // 2.5 Store Rating (Custom Metric for Seller/Admin)
        if ($isSeller) {
            $storeRating = Review::whereHas('itemShop', fn($q) => $q->where('user_id', $user->id))->avg('rating') ?: 0;
        } else {
            $storeRating = Review::avg('rating') ?: 0;
        }
        
        // 3. Fetch all paid/completed transactions for detailed analysis
        $transactions = Transaction::whereIn('status', ['paid', 'success', 'shipped', 'completed'])->get();

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

        $bestSellers = ItemShop::when(!$isAdmin && $isSeller, fn($q) => $q->where('user_id', $user->id))
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();

        // 8. Notifications from Database
        $notifications = \App\Models\Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
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
            'revenueTimeline',
            'userTransactionsCount',
            'userFavoritesCount',
            'userReviewsCount',
            'recentTransactions',
            'storeRating',
            'notifications',
            'platformBalance'
        ));
    }

    /**
     * Mark all notifications as read
     */
    public function markAsRead()
    {
        \App\Models\Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }
}
