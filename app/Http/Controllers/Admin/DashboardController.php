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

        // Handle Export Request
        if ($request->has('export') && $request->get('export') === 'excel') {
            return Excel::download(new TransactionExport, 'transactions_report_' . date('Y-m-d') . '.xlsx');
        }

        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $isSeller = $user->hasRole('seller');
        $isCourier = $user->hasRole('courier');

        // 1. Basic Stats (Admin/Seller) - Each user sees only their own items
        $totalItems = ItemShop::where('user_id', $user->id)->count();
        $totalUsers = User::count();
        
        // Count both Product Reviews and Seller Reviews
        if ($isAdmin) {
             $totalReviews = Review::count() + \App\Models\SellerReview::count();
        } else {
             $productReviewsCount = Review::whereHas('itemShop', fn($iq) => $iq->where('user_id', $user->id))->count();
             $sellerReviewsCount = \App\Models\SellerReview::where('seller_id', $user->id)->count();
             $totalReviews = $productReviewsCount + $sellerReviewsCount;
        }

        // 2. Role-Aware Detailed Stats
        $platformBalance = $isAdmin ? ($user->balance ?? 0) : 0;
        $userFavoritesCount = $user->favoriteItems()->count();
        $userReviewsCount = Review::where('user_id', $user->id)->count() + \App\Models\SellerReview::where('buyer_id', $user->id)->count();
        $outOfStockItems = 0;
        $storeRating = $user->seller_rating ?? 0;
        $pendingDeliveries = 0;
        $totalDeliveries = 0;
        $salesTransactions = collect();

        if ($isAdmin) {
            // Admin Privacy Fix: Admin can only see history of items they OWN as a seller
            $salesTransactions = Transaction::with(['user', 'seller'])
                ->where('seller_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
            $pendingOrders = Transaction::where('status', 'paid')->count();
            $sellerTotalItems = 0; 
        } elseif ($isSeller) {
            $salesTransactions = Transaction::with('user')
                ->where('seller_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
            $pendingOrders = Transaction::where('status', 'paid')
                ->get()
                ->filter(function($tx) use ($user) {
                    $details = is_array($tx->items_details) ? $tx->items_details : json_decode($tx->items_details, true);
                    return collect($details)->contains(function($item) use ($user) {
                        return ($item['user_id'] ?? $item['seller_id'] ?? null) == $user->id;
                    });
                })->count();
            $outOfStockItems = ItemShop::where('user_id', $user->id)->where('stok', '<=', 0)->count();
            $sellerTotalItems = ItemShop::where('user_id', $user->id)->count();
        } elseif ($isCourier) {
            $salesTransactions = Transaction::with('user')
                ->where('courier_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
            $pendingDeliveries = Transaction::where('courier_id', $user->id)
                ->where('status', 'shipped')
                ->count();
            $totalDeliveries = Transaction::where('courier_id', $user->id)
                ->whereIn('status', ['delivered', 'completed'])
                ->count();
            $sellerTotalItems = 0;
            $pendingOrders = 0;
        } else {
            // General User (Bolo)
            $sellerTotalItems = 0;
            $pendingOrders = 0;
        }

        // Always get Personal Purchases for the "Personal Order History" section
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'orders_page');
        
        $userTransactionsCount = Transaction::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'success', 'shipped', 'completed', 'waiting_confirmation'])
            ->count();

        // Check if platform has any items at all
        $platformHasItems = ItemShop::exists();
        $userHasTransactions = Transaction::where('user_id', $user->id)->exists();
        
        // Profile completeness check for address & pinpoint
        $profileIncomplete = empty($user->alamat) || empty($user->latitude) || empty($user->longitude);

        // 3. Fetch all paid/completed transactions for detailed analysis
        $transactions = Transaction::whereIn('status', ['paid', 'success', 'shipped', 'completed'])->get();

        // 4. Category Filter Logic
        $selectedCategory = $request->get('category', 'all');
        $categories = ItemShop::distinct()->pluck('kategori')->filter();

        // 5. Detailed Metrics Logic (Revenue by Item/Category & Stats Cleanup)
        $itemStats = [];
        $totalEarnings = 0;
        $platformProfit = 0;
        $totalOrdersCount = 0;

        foreach ($transactions as $transaction) {
            // Platform Profit for Admin (Admin Fee + Commission)
            if ($isAdmin) {
                $commissionPercent = (float)(\App\Models\SiteSetting::where('key', 'seller_commission_pct')->value('value') ?? 5);
                $itemsPrice = $transaction->total_price - $transaction->shipping_fee - $transaction->admin_fee;
                $commissionAmount = ($itemsPrice * $commissionPercent) / 100;
                $platformProfit += ($transaction->admin_fee + $commissionAmount);
            }

            // Courier logic: Earnings come from shipping_fee
            if ($isCourier) {
                if ($transaction->courier_id == $user->id) {
                    $totalEarnings += $transaction->shipping_fee;
                    $totalOrdersCount++;
                }
                continue;
            }

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
                
                if ($isCourier) {
                    if ($transaction->courier_id == $user->id) {
                        $dayTotal += $transaction->shipping_fee;
                    }
                    continue;
                }

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

        // 8. Notifications from Database - Show both read & unread (Latest 15)
        $notifications = \App\Models\Notification::where('user_id', $user->id)
            ->latest()
            ->take(15)
            ->get();

        // 9. Growth Metrics (This week vs Last week)
        $thisWeekStart = Carbon::now()->startOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        $itemGrowth = 0;
        $orderGrowth = 0;
        $userGrowth = 0;
        
        $activePromotions = 0; // Placeholder for future use

        // Item Growth
        $lastWeekItems = ItemShop::when(!$isAdmin && $isSeller, fn($q) => $q->where('user_id', $user->id))
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();
        $thisWeekItems = ItemShop::when(!$isAdmin && $isSeller, fn($q) => $q->where('user_id', $user->id))
            ->where('created_at', '>=', $thisWeekStart)
            ->count();
        $itemGrowth = $lastWeekItems > 0 ? (($thisWeekItems - $lastWeekItems) / $lastWeekItems) * 100 : ($thisWeekItems > 0 ? 100 : 0);

        // User Growth (Admin Only)
        $lastWeekUsers = User::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        $thisWeekUsers = User::where('created_at', '>=', $thisWeekStart)->count();
        $userGrowth = $lastWeekUsers > 0 ? (($thisWeekUsers - $lastWeekUsers) / $lastWeekUsers) * 100 : ($thisWeekUsers > 0 ? 100 : 0);

        // Order Growth
        $lastWeekOrders = Transaction::whereIn('status', ['paid', 'success', 'shipped', 'completed'])
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();
        $thisWeekOrders = Transaction::whereIn('status', ['paid', 'success', 'shipped', 'completed'])
            ->where('created_at', '>=', $thisWeekStart)
            ->count();
        
        // Logical Fix: If no orders last week but there are orders this week, growth is +100%. 
        // If no orders last week AND no orders this week, growth is 0%.
        if ($lastWeekOrders > 0) {
            $orderGrowth = (($thisWeekOrders - $lastWeekOrders) / $lastWeekOrders) * 100;
        } else {
            $orderGrowth = $thisWeekOrders > 0 ? 100 : 0;
        }

        // 10. Seller Analytics (Product Performance)
        $productPerformance = collect();
        $topProducts = collect();
        $sellerRatingData = null;
        
        if ($isSeller || $isAdmin) {
            // Top selling products for this seller
            $topProducts = ItemShop::where('user_id', $user->id)
                ->orderByDesc('total_terjual')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'photo' => $item->photo,
                        'sold' => $item->total_terjual,
                        'stock' => $item->stok,
                        'price' => $item->price,
                        'revenue' => $item->price * $item->total_terjual,
                    ];
                });

            // Product performance by category
            $productPerformance = ItemShop::where('user_id', $user->id)
                ->selectRaw('kategori, COUNT(*) as count, SUM(total_terjual) as total_sold, SUM(stok) as total_stock')
                ->groupBy('kategori')
                ->get();

            // Seller rating data
            $sellerRatingData = [
                'rating' => $user->seller_rating ?? 0,
                'count' => $user->seller_rating_count ?? 0,
                'is_top_seller' => $user->is_top_seller ?? false,
            ];
        }

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
            'salesTransactions',
            'storeRating',
            'notifications',
            'platformBalance',
            'platformProfit',
            'itemGrowth',
            'orderGrowth',
            'userGrowth',
            'pendingOrders',
            'outOfStockItems',
            'platformHasItems',
            'userHasTransactions',
            'sellerTotalItems',
            'profileIncomplete',
            'isCourier',
            'pendingDeliveries',
            'totalDeliveries',
            'productPerformance',
            'topProducts',
            'sellerRatingData',
        ));
    }

    /**
     * Mark notification(s) as read
     */
    public function markAsRead($id = null)
    {
        if ($id) {
            \App\Models\Notification::where('user_id', Auth::id())
                ->where('id', $id)
                ->update(['is_read' => true]);
            $message = 'Notifikasi telah dibaca.';

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
        } else {
            \App\Models\Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
            $message = 'Semua notifikasi ditandai telah dibaca.';
        }

        return back()->with('success', $message);
    }

    /**
     * Broadcast notification to all users
     */
    public function broadcast(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:255',
        ]);

        $users = \App\Models\User::all();
        foreach ($users as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => 'broadcast',
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Pengumuman berhasil disebarkan ke seluruh bolo!');
    }

    /**
     * Delete notification
     */
    public function destroyNotification($id)
    {
        \App\Models\Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Delete all notifications for current user
     */
    public function destroyAllNotifications()
    {
        \App\Models\Notification::where('user_id', Auth::id())->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi berhasil dihapus bolo!');
    }
}
