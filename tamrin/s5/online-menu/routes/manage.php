<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Manage\CityController;
use App\Http\Controllers\Manage\MessageController;
use App\Http\Controllers\Manage\OrderController;
use App\Http\Controllers\Manage\ProductController;
use App\Http\Controllers\Manage\ProvinceController;
use App\Http\Controllers\Manage\UserController;

Route::middleware('auth:sanctum')->prefix('manage')->name('manage.')->group(function () {
    Route::get('products/{product}/report', [ProductController::class, 'reportProduct']);
    Route::get('products/report', [ProductController::class, 'report']);
    Route::apiResource('products', ProductController::class);
    Route::get('users/{user}/report', [UserController::class, 'reportUser']);
    Route::get('users/report', [UserController::class, 'report']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('messages', MessageController::class)->except(['store']);
    Route::apiResource('provinces', ProvinceController::class);
    Route::apiResource('cities', CityController::class);
    Route::get('orders/{order}/report', [OrderController::class, 'reportOrder']);
    Route::get('orders/report', [OrderController::class, 'report']);
    Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
});
