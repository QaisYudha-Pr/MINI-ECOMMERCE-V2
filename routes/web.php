<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemShopController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

Route::get('/test', function () {
    return view('test');
});

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/item-shop/public', [ItemShopController::class, 'public'])
    ->name('item-shop.public');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk/{itemShop}', [ItemShopController::class, 'show'])->name('produk.show');


/* // Route dashboard dan Produuk(resource) hanya bisa diakses oleh user yang sudah login*/

Route::resource('item-shop', ItemShopController::class);
Route::post('/item-shop/{id}/review', [ItemShopController::class, 'storeReview'])->name('reviews.store')->middleware('auth');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    Route::resource('item-shop', ItemShopController::class)
        ->except(['show', 'public']);

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
});

Route::get('admin', function () {
    return '<h1>Hai min</h1>';
})->middleware(['auth', 'verified', 'role:admin']);


// rute checkout/cart
Route::middleware('auth')->group(function () {
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
});
Route::get('/transactions', [CartController::class, 'index'])->name('transactions.index')->middleware('auth'); //histori
// 1. Halaman View Checkout (GET)
Route::get('/checkout', function () {
    return view('transactions.checkout'); // Pastikan pathnya resources/views/transactions/checkout.blade.php
})->middleware('auth')->name('checkout.index');

// 2. Proses Simpan Transaksi (POST) - Ini yang dipanggil AJAX Fetch
Route::post('/checkout/process', [CheckoutController::class, 'store'])
    ->middleware('auth')
    ->name('checkout.process');
Route::get('/checkout/process', function() {
    return redirect()->route('transactions.index')->with('success', 'Pembayaran diproses!');
})->middleware('auth');    

require __DIR__.'/auth.php';


// Tanpa prefix /api/
Route::post('/callback', [App\Http\Controllers\MidtransCallbackController::class, 'callback']);