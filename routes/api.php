<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeWebhookController;

Route::get('/products', [StoreProductController::class, 'index']);
Route::get('/products/{slug}', [StoreProductController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove']);
    Route::put('/cart/update/{productId}', [CartController::class, 'update']);
    Route::get('/cart/total', [CartController::class, 'total']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('orders', [OrderController::class, 'index']);
});

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', AdminProductController::class);
    Route::apiResource('categories', AdminCategoryController::class);
    Route::get('orders', [AdminOrderController::class, 'index']);
});