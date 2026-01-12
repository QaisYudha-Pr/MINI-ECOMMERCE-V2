<?php

namespace App\Http\Controllers;

use App\Models\ItemShop;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return view('dashboard', [
                'totalItem' => ItemShop::count(),
                'totalUser' => User::count(),
            ]);
        }

        return view('dashboard', [
            'totalItem' => $user->itemShops()->count(),
        ]);
    }
}

