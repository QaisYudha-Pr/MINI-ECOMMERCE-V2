<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\User\ProfileController;
use App\Http\Controllers\Shop\User\FavoriteController;
use App\Http\Controllers\Shop\User\ReviewController;
use App\Http\Controllers\Shop\User\SellerController;
use App\Http\Controllers\Shop\SearchController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SellerValidationController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\ItemShopController as AdminItemShopController;
use App\Http\Controllers\Shop\ItemShopController as ShopItemShopController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Api\MidtransCallbackController;
 
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
 
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/test', fn() => view('test'));
Route::get('/about', fn() => view('about.mstore-about'))->name('about');
 
// Public Shop Routes
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopItemShopController::class, 'public'])->name('public');
    Route::get('/items/{itemShop}', [ShopItemShopController::class, 'show'])->name('show');
    Route::get('/stats', [ShopItemShopController::class, 'stats'])->name('stats');
});
 
Route::post('/callback', [MidtransCallbackController::class, 'callback']);
 
/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
 
Route::middleware('auth')->group(function () {
 
    // Dashboard for both Admin and User
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::redirect('/admin/dashboard', '/dashboard');
    Route::redirect('/user/dashboard', '/dashboard');

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::patch('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
    });
 
    // Favorites
    Route::post('/favorite/{item}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
 
    // Seller Registration
    Route::get('/become-seller', [SellerController::class, 'create'])->name('seller.create');
    Route::post('/become-seller', [SellerController::class, 'store'])->name('seller.store');
 
    // Shop Interaction (Cart & Checkout)
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', fn() => view('shop.checkout.index'))->name('index');
        Route::post('/', [CartController::class, 'checkout'])->name('cart'); 
        Route::post('/process', [CheckoutController::class, 'store'])->name('process');
    });
 
    Route::get('/transactions', [CartController::class, 'index'])->name('transactions.index');
    Route::post('/item-shop/{itemShop}/review', [ReviewController::class, 'store'])->name('reviews.store');
 
    /*
    |--------------------------------------------------------------------------
    | Admin & Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
 
        // Product Management (Resource for internal use)
        Route::middleware('role_or_permission:seller|admin|tambah-produk|edit-produk|hapus-produk')->group(function () {
            Route::resource('item-shop', AdminItemShopController::class)->except(['show']);
        });
 
        // User Management
        Route::middleware('role_or_permission:admin|lihat-user|tambah-user|edit-user|hapus-user')->group(function () {
            Route::resource('users', UserController::class);
            Route::get('seller-requests', [SellerValidationController::class, 'index'])->name('admin.sellers.index');
            Route::post('seller-requests/{user}/approve', [SellerValidationController::class, 'approve'])->name('admin.sellers.approve');
            Route::post('seller-requests/{user}/reject', [SellerValidationController::class, 'reject'])->name('admin.sellers.reject');
        });

        // CMS Settings
        Route::middleware('role:admin')->prefix('cms')->name('admin.cms.')->group(function () {
            Route::get('/', [CmsController::class, 'index'])->name('index');
            Route::post('/update-logo', [CmsController::class, 'updateLogo'])->name('update-logo');
            Route::post('/update-text', [CmsController::class, 'updateText'])->name('update-text');
            Route::post('/update-images', [CmsController::class, 'updateImages'])->name('update-images');
        });
 
        Route::get('/', fn() => '<h1>Hai min</h1>')->middleware('role:admin');
    });
});
 
require __DIR__ . '/auth.php';
