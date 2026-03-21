<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

Route::get('/products', [StoreProductController::class, 'index']);
Route::get('/products/{slug}', [StoreProductController::class, 'show']);

Route::prefix('admin')->group(function () {
    Route::apiResource('products', AdminProductController::class);
    Route::apiResource('categories', AdminCategoryController::class);

});