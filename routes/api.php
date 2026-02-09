<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RealtimeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/midtrans/callback', [App\Http\Controllers\Api\MidtransCallbackController::class, 'callback']);

// Real-time polling endpoints (authentication via session/Sanctum)
Route::middleware('auth:sanctum')->prefix('realtime')->group(function () {
    Route::get('/notifications', [RealtimeController::class, 'pollNotifications']);
    Route::get('/notifications/counts', [RealtimeController::class, 'notificationCounts']);
    Route::get('/messages/{conversation}', [RealtimeController::class, 'pollMessages']);
    Route::get('/messages/unread/count', [RealtimeController::class, 'unreadMessageCount']);
});