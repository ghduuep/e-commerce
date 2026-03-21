use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

Route::get('/products', [StoreProductController::class, 'index']);
Route::get('/products/{slug}', [StoreProductController::class, 'show']);

Route::prefix('admin')->group(function () {
    Route::apiResource('products', AdminProductController::class);
});