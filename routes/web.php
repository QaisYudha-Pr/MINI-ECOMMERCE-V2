<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\produk\ProdukController;
use App\Http\Controllers\ItemShopController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::get('/search', [SearchController::class, 'search'])->name('search');   
Route::get('/item-shop/public', [ItemShopController::class, 'public'])
    ->name('item-shop.public');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk/{itemShop}', [ItemShopController::class, 'show'])->name('produk.show');


/* // Route dashboard dan Produuk(resource) hanya bisa diakses oleh user yang sudah login*/



Route::resource('item-shop', ItemShopController::class);


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    Route::resource('item-shop', ItemShopController::class)
        ->except(['show', 'public']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/users-manage', [UserController::class, 'index'])
            ->name('users.index');
        Route::resource('users', UserController::class);
    });
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('admin', function () {
    return '<h1>Hai min</h1>';
})->middleware(['auth', 'verified', 'role:admin']);

require __DIR__.'/auth.php';
