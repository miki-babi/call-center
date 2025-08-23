<?php

use App\Helpers\Chapa;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MapController;
use App\Models\Shop;
// use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;




Route::get('/callback/{shop}/{order}', function (Request $request, $shop, $order) {
    $data = $request->all();
    


    Log::info("Chapa callback received for shop: {$shop}, order: {$order}", $data);

    // Process the callback data as needed
    $shopOrder= Shop::where('name', $shop)->first();

    if (isset($data['status']) && $data['status'] === 'success') {

       $orderPayload = ['status' => 'processing'];

$response = Http::withBasicAuth($shopOrder->consumer_key, $shopOrder->consumer_secret)
    ->put($shopOrder->url . '/wp-json/wc/v3/orders/' . $order, $orderPayload);

if ($response->successful()) {
    Log::info("Order {$order} status updated to processing");
} else {
    Log::error("Failed to update order {$order}", $response->json());
}


        Log::info("Payment successful for transaction: " . ($data['trx_ref'] ?? 'N/A'));
    } else {
        Log::warning("Payment failed or pending for transaction: " . ($data['trx_ref'] ?? 'N/A'));
    }

    return [
        'shop' => $shop,
        'order' => $order,
        'callback' => $data
    ];
})->name('callback');
Route::get('/chapa/verify/{order_id}', function ($order_id) {

    if(!$order_id) {
        return "Order ID is required";
    }
    if ($order_id != "test") {
        return "Order ID is not test";
    }

    // return $data;
});
Route::get('/chapa/{shop}/{order_id}', function ($shop, $order_id) {

    $data=Chapa::initiate($shop, $order_id);

    return $data;
})->name('chapa');


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
