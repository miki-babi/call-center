<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Models\Farmer;
use Illuminate\Support\Facades\Artisan;

Route::post('login', [SessionController::class,'login'])->name('auth.login');
Route::post('logout', [SessionController::class,'logout'])->name('auth.logout');
Route::get('/login', function(){
    return view('auth.login');
})->name('auth.form');

// Exclude /clear-all and /sync-woo from auth protection
Route::get('/clear-all', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    // Composer dump-autoload (optional and external)
    // $composerDump = shell_exec('composer dump-autoload');
    return response()->json([
        'message' => 'All Laravel caches cleared!',
        // 'composer' => $composerDump ?? 'composer dump-autoload not executed',
    ]);
});
Route::get('/link', function () {
    Artisan::call('storage:link');
    // Artisan::call('cache:clear');
    // Artisan::call('route:clear');
    // Artisan::call('view:clear');
    // Artisan::call('optimize:clear');
    // Composer dump-autoload (optional and external)
    // $composerDump = shell_exec('composer dump-autoload');
    return response()->json([
        'message' => 'All Laravel caches cleared!',
        // 'composer' => $composerDump ?? 'composer dump-autoload not executed',
    ]);
});
Route::get('/sync-woo', function () {
    Artisan::call('sync-woo');
    return response()->json([
        'message' => 'SyncWoo command executed.',
        'output' => Artisan::output(),
    ]);
});

    Route::get('/dashboard', function(){
        return view('dashboard');
    })->name('dashboard');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/product', [ProductController::class, 'fetchAllProducts'])->name('product.index');
    Route::get('/shop/orders', [OrderController::class, 'fetchAllOrders'])->name('orders.fetchAll');
    Route::get('/shop/orders/{name}', [OrderController::class, 'fetchOrders'])->name('orders.fetch');

