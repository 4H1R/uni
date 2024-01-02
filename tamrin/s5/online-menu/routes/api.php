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

use App\Http\Controllers\Main\CityController;
use App\Http\Controllers\Main\OrderController;
use App\Http\Controllers\Main\ProductController;
use App\Http\Controllers\Main\ProvinceController;
use App\Http\Controllers\Main\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/me', [UserController::class, 'me'])->name('users.me');
    Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
});

Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('provinces', ProvinceController::class)->only(['index', 'show']);
Route::apiResource('cities', CityController::class)->only(['index', 'show']);

require __DIR__.'/manage.php';
