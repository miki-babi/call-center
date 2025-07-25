<?php


use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\MapController;



Route::post('login', [SessionController::class, 'login'])->name('auth.login');
Route::post('logout', [SessionController::class, 'logout'])->name('auth.logout');
Route::get('/login', function () {
    return view('auth.login');
})->name('auth.form');

// Exclude /clear-all and /sync-woo from auth protection
Route::get('/clear-all', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return response()->json([
        'message' => 'All Laravel caches cleared!',
    ]);
});
Route::get('/link', function () {
    Artisan::call('storage:link');
    return response()->json([
        'message' => 'All Laravel caches cleared!',
    ]);
});
Route::get('/sync-woo', function () {
    Artisan::call('sync-woo');
    return response()->json([
        'message' => 'SyncWoo command executed.',
        'output' => Artisan::output(),
    ]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/map', [MapController::class, 'show']);
Route::get('/leaflet/search', [MapController::class, 'search']);
Route::get('/leaflet/distance', [MapController::class, 'getDistance']);



// Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/order/new', [OrderController::class, 'newOrder'])->name('orders.new');
Route::get('/order/new/{branch}', [OrderController::class, 'branch'])->name('orders.new.branch');
Route::get('/product', [ProductController::class, 'fetchAllProducts'])->name('product.index');
Route::get('/shop/orders', [OrderController::class, 'fetchAllOrders'])->name('orders.index');
Route::get('/shop/orders/{name}', [OrderController::class, 'fetchOrders'])->name('orders.fetch');
