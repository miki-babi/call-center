<?php

use App\Helpers\Chapa;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MapController;
// use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



Route::post('/callback', function (Request $request) {

    Log::info('Chapa callback received:', $request->all());
    $data=Chapa::initiate($order_id="test");

    return $data;
})->name('callback');

Route::get('/chapa', function () {

    $data=Chapa::initiate($order_id="test");

    return $data;
});

Route::get('/chapa/verify/{order_id}', function ($order_id) {

    if(!$order_id) {
        return "Order ID is required";
    }
    if($order_id=="test") {
        return "Order ID is test";
    }

    // return $data;
});
// Route::post('login', [SessionController::class, 'login'])->name('auth.login');
// Route::post('logout', [SessionController::class, 'logout'])->name('auth.logout');
// Route::get('/login', function () {
//     return view('auth.login');
// })->name('auth.form');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');
// Route::get('/map', [MapController::class, 'show']);
// Route::get('/leaflet/search', [MapController::class, 'search']);
// Route::get('/leaflet/distance', [MapController::class, 'getDistance']);
// Route::get('/order/new', [OrderController::class, 'newOrder'])->name('orders.new');
// Route::post('/order/new', [OrderController::class, 'placeOrder'])->name('orders.place.new');
// Route::get('/order/new/{branch}', [OrderController::class, 'branch'])->name('orders.new.branch');
// Route::get('/product', [ProductController::class, 'fetchAllProducts'])->name('product.index');
// Route::get('/shop/orders', [OrderController::class, 'fetchAllOrders'])->name('orders.index');
// Route::get('/shop/orders/{name}', [OrderController::class, 'fetchOrders'])->name('orders.fetch');


Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::get('/', function () {
        return view('auth.login');
    });
    Route::post('login', [SessionController::class, 'login'])->name('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [SessionController::class, 'logout'])->name('auth.logout');

    Route::get('/dashboard', function () {
        // return view('dashboard');
    })->name('dashboard');

    Route::get('/map', [MapController::class, 'show']);
    Route::get('/leaflet/search', [MapController::class, 'search']);
    Route::get('/leaflet/distance', [MapController::class, 'getDistance']);

    Route::get('/order/new', [OrderController::class, 'newOrder'])->name('orders.new');
    Route::post('/order/new', [OrderController::class, 'placeOrder'])->name('orders.place.new');
    Route::get('/order/new/{branch}', [OrderController::class, 'branch'])->name('orders.new.branch');

    Route::get('/product', [ProductController::class, 'fetchAllProducts'])->name('product.index');
    Route::get('/shop/orders', [OrderController::class, 'fetchAllOrders'])->name('orders.index');
    Route::get('/shop/orders/{name}', [OrderController::class, 'fetchOrders'])->name('orders.fetch');
});
