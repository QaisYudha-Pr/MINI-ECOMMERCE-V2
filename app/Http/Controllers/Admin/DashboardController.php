<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemShop;
use App\Models\User;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        // Data for charts
        $totalItems = ItemShop::count();
        $totalUsers = User::count();
        $totalReviews = Review::count();
        $totalTransactions = Transaction::count();

        // Best sellers (dummy logic for now, or use real data if available)
        $bestSellers = ItemShop::orderBy('total_terjual', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalItems',
            'totalUsers',
            'totalReviews',
            'totalTransactions',
            'bestSellers'
        ));
    }
}
