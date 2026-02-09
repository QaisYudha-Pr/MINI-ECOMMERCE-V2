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
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Shop\ItemShopController as ShopItemShopController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Api\MidtransCallbackController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Shop\User\SellerReviewController;
 
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
    Route::post('/notifications/mark-as-read/{id?}', [DashboardController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::match(['delete', 'post'], '/notifications/{id}/delete', [DashboardController::class, 'destroyNotification'])->name('notifications.destroy');
    Route::post('/notifications/delete-all', [DashboardController::class, 'destroyAllNotifications'])->name('notifications.destroy-all');
    Route::post('/notifications/broadcast', [DashboardController::class, 'broadcast'])->name('notifications.broadcast');
    Route::get('/how-to', fn() => view('admin.how-to'))->name('how-to');
    Route::redirect('/admin/dashboard', '/dashboard');
    Route::redirect('/user/dashboard', '/dashboard');

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::patch('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar.update');
        Route::patch('/banner', [ProfileController::class, 'updateBanner'])->name('banner.update');
        Route::post('/quick-address', [ProfileController::class, 'updateQuickAddress'])->name('update-quick-address');
        Route::patch('/theme', [ProfileController::class, 'updateTheme'])->name('theme.update');
    });

    // Follow system
    Route::post('/follow/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');
    Route::post('/notifications/read-all', function() {
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['status' => 'success']);
    })->name('notifications.read-all');
 
    // Favorites
    Route::get('/wishlist', [FavoriteController::class, 'index'])->name('wishlist.index');
    Route::post('/favorite/{item}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    // Cart Management
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'cartIndex'])->name('index');
        Route::post('/add', [CartController::class, 'addToCart'])->name('add');
        Route::patch('/{cart}', [CartController::class, 'updateCart'])->name('update');
        Route::delete('/{cart}', [CartController::class, 'removeFromCart'])->name('remove');
        Route::delete('/', [CartController::class, 'clearCart'])->name('clear');
        Route::post('/apply-voucher', [CartController::class, 'applyVoucher'])->name('apply-voucher');
        Route::get('/count', [CartController::class, 'cartCount'])->name('count');
    });

    // Chat/Inbox System
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/unread/count', [ChatController::class, 'unreadCount'])->name('unread');
        Route::get('/start/{seller}', [ChatController::class, 'startChat'])->name('start');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::get('/{conversation}/poll', [ChatController::class, 'pollMessages'])->name('poll');
        Route::post('/', [ChatController::class, 'store'])->name('store');
        Route::post('/{conversation}/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::post('/seller/{seller}', [ChatController::class, 'startFromProduct'])->name('startProduct');
        Route::put('/message/{message}', [ChatController::class, 'editMessage'])->name('message.edit');
        Route::delete('/message/{message}', [ChatController::class, 'deleteMessage'])->name('message.delete');
    });
 
    // Seller Registration
    Route::get('/become-seller', [SellerController::class, 'create'])->name('seller.create');
    Route::post('/become-seller', [SellerController::class, 'store'])->name('seller.store');
 
    // Shop Interaction (Cart & Checkout)
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'checkout'])->name('cart'); 
        Route::post('/process', [CheckoutController::class, 'store'])->name('process');
    });
 
    Route::get('/transactions', [CartController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [CartController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/confirm', [CartController::class, 'confirmReceipt'])->name('transactions.confirm');
    Route::post('/transactions/{transaction}/change-payment', [CartController::class, 'changePayment'])->name('transactions.changePayment');
    
    Route::get('/my-reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/item-shop/{itemShop}/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/transactions/{transaction}/seller-review', [SellerReviewController::class, 'store'])->name('seller-reviews.store');
 
    // Shipping Routes
    Route::prefix('shipping')->name('shipping.')->group(function () {
        Route::get('/search-area', [ShippingController::class, 'searchArea'])->name('search-area');
        Route::post('/get-rates', [ShippingController::class, 'getRates'])->name('get-rates');
        Route::post('/seller-coordinates', [ShippingController::class, 'getSellerCoordinates'])->name('seller-coordinates');
    });

    // Courier Specific Routes (DIPISAH DARI ADMIN)
    Route::middleware(['auth', 'role:courier'])->prefix('courier')->name('courier.')->group(function () {
        Route::get('/deliveries', [\App\Http\Controllers\Courier\DeliveryController::class, 'index'])->name('deliveries.index');
        Route::get('/deliveries/{transaction}', [\App\Http\Controllers\Courier\DeliveryController::class, 'show'])->name('deliveries.show');
        Route::post('/deliveries/{transaction}/complete', [\App\Http\Controllers\Courier\DeliveryController::class, 'complete'])->name('deliveries.complete');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin & Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
 
        // Transaction Management
        Route::middleware('role:admin|seller')->group(function () {
            Route::get('transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('admin.transactions.index');
            Route::get('transactions/{transaction}', [\App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('admin.transactions.show');
            Route::post('transactions/{transaction}/resi', [\App\Http\Controllers\Admin\TransactionController::class, 'updateResi'])->name('admin.transactions.resi');
            Route::post('transactions/{transaction}/assign-courier', [\App\Http\Controllers\Admin\TransactionController::class, 'updateCourier'])->name('admin.transactions.assign');
            Route::post('transactions/{transaction}/confirm-cod', [\App\Http\Controllers\Admin\TransactionController::class, 'confirmCod'])->name('admin.transactions.confirm-cod');
        });

        // Product Management (Resource for internal use)
        Route::middleware('role_or_permission:seller|admin|tambah-produk|edit-produk|hapus-produk')->group(function () {
            Route::get('item-shop/export', [AdminItemShopController::class, 'export'])->name('item-shop.export');
            Route::post('item-shop/{itemShop}/quick-stock', [AdminItemShopController::class, 'quickStock'])->name('item-shop.quick-stock');
            Route::resource('item-shop', AdminItemShopController::class)->except(['show']);
        });
 
        // User Management
        Route::middleware('role_or_permission:admin|lihat-user|tambah-user|edit-user|hapus-user')->group(function () {
            Route::resource('users', UserController::class);
            Route::get('seller-requests', [SellerValidationController::class, 'index'])->name('admin.sellers.index');
            Route::post('seller-requests/{user}/approve', [SellerValidationController::class, 'approve'])->name('admin.sellers.approve');
            Route::post('seller-requests/{user}/reject', [SellerValidationController::class, 'reject'])->name('admin.sellers.reject');
        });

        // Withdrawals
        Route::middleware('role:admin|seller|courier')->prefix('withdrawals')->name('admin.withdrawals.')->group(function () {
            Route::get('/', [WithdrawalController::class, 'index'])->name('index');
            Route::post('/', [WithdrawalController::class, 'store'])->name('store');
            Route::put('/{withdrawal}', [WithdrawalController::class, 'update'])->name('update');
        });

        // Voucher Management
        Route::middleware('role:admin|seller')->prefix('vouchers')->name('admin.vouchers.')->group(function () {
            Route::get('/', [VoucherController::class, 'index'])->name('index');
            Route::get('/create', [VoucherController::class, 'create'])->name('create');
            Route::post('/', [VoucherController::class, 'store'])->name('store');
            Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
            Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');
            Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
            Route::post('/{voucher}/toggle', [VoucherController::class, 'toggle'])->name('toggle');
        });

        // CMS Settings
        Route::middleware('role:admin')->prefix('cms')->name('admin.cms.')->group(function () {
            Route::get('/', [CmsController::class, 'index'])->name('index');
            Route::post('/update-logo', [CmsController::class, 'updateLogo'])->name('update-logo');
            Route::post('/update-text', [CmsController::class, 'updateText'])->name('update-text');
            Route::post('/update-images', [CmsController::class, 'updateImages'])->name('update-images');
            Route::post('/update-settings', [CmsController::class, 'updateSettings'])->name('settings.update');
            Route::post('/delete-slider', [CmsController::class, 'deleteSlider'])->name('delete-slider');
            Route::post('/reset', [CmsController::class, 'resetToDefault'])->name('reset');
            Route::get('/revenue/export', [CmsController::class, 'exportRevenue'])->name('revenue.export');

            // Courier Management
            Route::post('/couriers', [CmsController::class, 'storeCourier'])->name('couriers.store');
            Route::put('/couriers/{courier}', [CmsController::class, 'updateCourier'])->name('couriers.update');
            Route::post('/couriers/{courier}/toggle', [CmsController::class, 'toggleCourier'])->name('couriers.toggle');
            Route::delete('/couriers/{courier}', [CmsController::class, 'deleteCourier'])->name('couriers.delete');
        });
 
        Route::get('/', fn() => '<h1>Hai min</h1>')->middleware('role:admin');
    });
});
 
require __DIR__ . '/auth.php';