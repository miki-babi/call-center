<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Concurrency;


use function Termwind\parse;


class DashboardController extends Controller
{
    private $consumerKey_M;
    private $consumerSecret_M;
    private $storeUrl_M;
    private $wpapi_M;
    private $consumerKey_A;
    private $consumerSecret_A;
    private $storeUrl_A;
    private $wpapi_A;

    public function __construct()
    {
        $this->consumerKey_M = env('MX_CONSUMER_KEY');
        $this->consumerSecret_M = env('MX_CONSUMER_SECRET');
        $this->storeUrl_M = env('MX_STORE_URL');
        $this->wpapi_M = env('MX_API');
        $this->consumerKey_A = env('AY_CONSUMER_KEY');
        $this->consumerSecret_A = env('AY_CONSUMER_SECRET');
        $this->storeUrl_A = env('AY_STORE_URL');
        $this->wpapi_A = env('AY_API');
    }

 public function MX_create(){
    return view('order.create-mexico', );
 }
 public function AY_create(){
    return view('order.create-ayat', );
 }
//Mexico methods

    public function MX_orders($page)
{
    $page = $page ?? 1; // Use the provided page or default to 1
    $perPage = 30; // Number of orders per page

    
//pending
    $response_M_pending = Http::withBasicAuth($this->consumerKey_M, $this->consumerSecret_M)
        ->get("{$this->storeUrl_M}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['pending'] // Default to 'any' if no status is provided
        ]);

    

    $pendings = $response_M_pending->json();

   
    //cancelled
    $response_M_cancelled = Http::withBasicAuth($this->consumerKey_M, $this->consumerSecret_M)
        ->get("{$this->storeUrl_M}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['cancelled'] // Default to 'any' if no status is provided
        ]);
    $cancelleds = $response_M_cancelled->json();


    //on-hold
    $response_M_on_hold = Http::withBasicAuth($this->consumerKey_M, $this->consumerSecret_M)
        ->get("{$this->storeUrl_M}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['on-hold'] // Default to 'any' if no status is provided
        ]);
    $on_holds = $response_M_on_hold->json();

    //failed
    $response_M_failed = Http::withBasicAuth($this->consumerKey_M, $this->consumerSecret_M)
        ->get("{$this->storeUrl_M}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['failed'] // Default to 'any' if no status is provided
        ]);
    $faileds = $response_M_failed->json();


    //completed
    $response_M_completed = Http::withBasicAuth($this->consumerKey_M, $this->consumerSecret_M)
        ->get("{$this->storeUrl_M}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['completed'] // Default to 'any' if no status is provided
        ]);
    $completeds = $response_M_completed->json();
   


    //processing
    $response_M_processing = Http::withBasicAuth($this->consumerKey_M, $this->consumerSecret_M)
        ->get("{$this->storeUrl_M}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['processing'] // Default to 'any' if no status is provided
        ]);
    $processings = $response_M_processing->json();
 
    
 
//pass the data to the view

    return view('order.index-mexico', [
        'pendings' => $pendings,
        'cancelleds' => $cancelleds,
        'on_holds' => $on_holds,
        'faileds' => $faileds,
        'completeds' => $completeds,
        'processings' => $processings,
    

    ]);
}
public function MX_createOrder(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'address_1' => 'required',
        'city' => 'required',
        'state' => 'required',
        'postcode' => 'required',
        'country' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'product_id' => 'required|array',
        'product_id.*' => 'required|integer',
        'quantity' => 'required|array',
        'quantity.*' => 'required|integer|min:1',
    ]);

    // Build line_items array dynamically
    $lineItems = [];
    foreach ($validated['product_id'] as $index => $productId) {
        $lineItems[] = [
            'product_id' => (int) $productId,
            'quantity' => (int) $validated['quantity'][$index],
        ];
    }

    $orderData = [
        'payment_method' => 'chapa', // Or whatever slug you use
        'payment_method_title' => 'Chapa Payment',
        'set_paid' => false,
        'billing' => [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'address_1' => $validated['address_1'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postcode' => $validated['postcode'],
            'country' => $validated['country'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ],
        'line_items' => $lineItems
    ];

    $response = Http::withBasicAuth($this->consumerKey_M, $this->consumerSecret_M)
        ->post("{$this->storeUrl_M}/wp-json/wc/v3/orders", $orderData);

    if (!$response->successful()) {
        return response()->json([
            'error' => 'Failed to create order',
            'details' => $response->body(),
        ], 500);
    }

    $order = $response->json();

    $orderId = $order['id'];
    $orderKey = $order['order_key'];

    $paymentUrl = "{$this->storeUrl_M}/checkout/order-pay/{$orderId}/?pay_for_order=true&key={$orderKey}";

    return redirect()->to('https://call-center.beshgebeya.co/dashboard/mexico/orders/1');
}

// method to return product 
public function MX_products($name)
{
    $response = Http::withBasicAuth($this->consumerKey_M, $this->consumerSecret_M)
        ->get($this->storeUrl_M . "/wp-json/wc/v3/products", [
            'search' => $name,
            'per_page' => 50
        ]);

    if ($response->failed()) {
        return response()->json(['error' => 'Failed to fetch products'], 500);
    }

    $products = $response->json();

    if (empty($products)) {
        return response()->json(['message' => 'No products found'], 404);
    }

    return response()->json($products);
}




// ayat methods
public function AY_orders($page)
{
    $page = $page ?? 1; // Use the provided page or default to 1
    $perPage = 30; // Number of orders per page

    
//pending
    $response_A_pending = Http::withBasicAuth($this->consumerKey_A, $this->consumerSecret_A)
        ->get("{$this->storeUrl_A}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['pending'] // Default to 'any' if no status is provided
        ]);


    $pendings = $response_A_pending->json();

    //cancelled
    $response_A_cancelled = Http::withBasicAuth($this->consumerKey_A, $this->consumerSecret_A)
        ->get("{$this->storeUrl_A}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['cancelled'] // Default to 'any' if no status is provided
        ]);
        

    $cancelleds = $response_A_cancelled->json();

    //on-hold
    $response_A_on_hold = Http::withBasicAuth($this->consumerKey_A, $this->consumerSecret_A)
        ->get("{$this->storeUrl_A}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['on-hold'] // Default to 'any' if no status is provided
        ]);

    $on_holds = $response_A_on_hold->json();



    //failed
    $response_A_failed = Http::withBasicAuth($this->consumerKey_A, $this->consumerSecret_A)
        ->get("{$this->storeUrl_A}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['failed'] // Default to 'any' if no status is provided
        ]);

    $faileds = $response_A_failed->json();
    
    //completed
    $response_A_completed = Http::withBasicAuth($this->consumerKey_A, $this->consumerSecret_A)
        ->get("{$this->storeUrl_A}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['completed'] // Default to 'any' if no status is provided
            // 'status' => ['cancelled', 'pending', 'on-hold','failed'] // Default to 'any' if no status is provided
        ]);


    $completeds = $response_A_completed->json();


    //processing
    $response_A_processing = Http::withBasicAuth($this->consumerKey_A, $this->consumerSecret_A)
        ->get("{$this->storeUrl_A}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['processing'] // Default to 'any' if no status is provided
        ]);
    $processings = $response_A_processing->json();
    
    
 
//pass the data to the view
    return view('order.index-ayat', [
        'pendings' => $pendings,
        'cancelleds' => $cancelleds,
        'on_holds' => $on_holds,
        'faileds' => $faileds,
        'completeds' => $completeds,
        'processings' => $processings,
    

    ]);
}
public function AY_createOrder(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'address_1' => 'required',
        'city' => 'required',
        'state' => 'required',
        'postcode' => 'required',
        'country' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'product_id' => 'required|array',
        'product_id.*' => 'required|integer',
        'quantity' => 'required|array',
        'quantity.*' => 'required|integer|min:1',
    ]);

    // Build line_items array dynamically
    $lineItems = [];
    foreach ($validated['product_id'] as $index => $productId) {
        $lineItems[] = [
            'product_id' => (int) $productId,
            'quantity' => (int) $validated['quantity'][$index],
        ];
    }

    $orderData = [
        'payment_method' => 'chapa', // Or whatever slug you use
        'payment_method_title' => 'Chapa Payment',
        'set_paid' => false,
        'billing' => [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'address_1' => $validated['address_1'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postcode' => $validated['postcode'],
            'country' => $validated['country'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ],
        'line_items' => $lineItems
    ];

    $response_A = Http::withBasicAuth($this->consumerKey_A, $this->consumerSecret_A)
        ->post("{$this->storeUrl_A}/wp-json/wc/v3/orders", $orderData);

    if (!$response_A->successful()) {
        return response()->json([
            'error' => 'Failed to create order',
            'details' => $response_A->body(),
        ], 500);
    }

    $order = $response_A->json();

    $orderId = $order['id'];
    $orderKey = $order['order_key'];

    $paymentUrl = "{$this->storeUrl_A}/checkout/order-pay/{$orderId}/?pay_for_order=true&key={$orderKey}";

    return redirect()->to('https://call-center.beshgebeya.co/dashboard/ayat/orders/1');

}
public function AY_products($name)
{
    $response = Http::withBasicAuth($this->consumerKey_A, $this->consumerSecret_A)
        ->get($this->storeUrl_A . "/wp-json/wc/v3/products", [
            'search' => $name,
            'per_page' => 50
        ]);

    if ($response->failed()) {
        return response()->json(['error' => 'Failed to fetch products'], 500);
    }

    $products = $response->json();

    if (empty($products)) {
        return response()->json(['message' => 'No products found'], 404);
    }

    return response()->json($products);
}
}
