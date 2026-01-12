<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\produk\ProdukController;


Route::get('/search', [SearchController::class, 'search'])->name('search');   

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk/{item}', [ProdukController::class, 'show'])
    ->name('produk.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('admin', function () {
    return '<h1>Hai min</h1>';
})->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
