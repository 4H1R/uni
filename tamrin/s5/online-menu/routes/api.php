<?php

use App\Http\Controllers\Main\CityController;
use App\Http\Controllers\Main\OrderController;
use App\Http\Controllers\Main\ProductController;
use App\Http\Controllers\Main\ProvinceController;
use App\Http\Controllers\Main\UserController;
use App\Http\Controllers\Main\WishlistController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/me', [UserController::class, 'me'])->name('users.me');
    Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
    Route::post('products/{product}/wishlists', [WishlistController::class, 'store']);
    Route::delete('products/{product}/wishlists', [WishlistController::class, 'destroy']);
    Route::get('wishlists', [WishlistController::class, 'index']);
});

Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('provinces', ProvinceController::class)->only(['index', 'show']);
Route::apiResource('cities', CityController::class)->only(['index', 'show']);

require __DIR__.'/manage.php';
