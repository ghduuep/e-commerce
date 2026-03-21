use App\Http\Controllers\Store\ProductController;

Route::get('/products', [ProductController::class], 'index');
Route::get('/products/{slug}', [ProductController::class], ' show');