<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

use function Termwind\parse;

class TestController extends Controller
{
    private $consumerKey;
    private $consumerSecret;
    private $storeUrl;
    private $wpapi;

    public function __construct()
    {
        $this->consumerKey = env('WC_CONSUMER_KEY');
        $this->consumerSecret = env('WC_CONSUMER_SECRET');
        $this->storeUrl = env('WC_STORE_URL');
        $this->wpapi = env('WP_API');
    }

    public function index()
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            // ->get("$this->storeUrl/wp-json/wp/v2/users");
            ->get("$this->storeUrl/wp-json/wc/v3/products?search=onion");
            // /wp-json/wc/v3/products?search=Cool Product


        if ($response->successful()) {
            // $customers = $response->json();
            $customers = $response->json(); // Automatically decodes JSON to an array

            return response()->json($customers);
            // foreach ($customers as $customer) {
            //     echo "ID: " . $customer['id'] . ", Name: " . $customer['first_name'] . " " . $customer['role'] . "\n";
            // }
            // return response()->json($customers);
        } else {
            return response()->json(['error' => 'Failed to fetch customers'], 500);
        }

        // dd('test');
    }

    public function show(Request $request)
    {
        // dd($request->email);
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($this->storeUrl . "/wp-json/wp/v3/customers", [
                'search' => $request->email
            ]);

        $users = $response->json();
        return response()->json($users);
    }
    public function something()
    {
        $username='admin';
        $response = Http::withBasicAuth($username,$this->wpapi)
            // ->get("$this->storeUrl/wp-json/wp/v2/users");
            // ->get("$this->storeUrl/wp-json/wp/v2/users?search=10minbook");
            ->get("$this->storeUrl/wp-json/wp/v2/users?role=admin");
            
            

        if ($response->successful()) {
            // $customers = $response->json();
            $customers = $response->json(); // Automatically decodes JSON to an array

            return response()->json($customers);
            
        } else {
            return response()->json(['error' => 'Failed to fetch customers'], 500);
        }

        // dd('test');
    }
//     
public function createOrder(Request $request)
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

    $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
        ->post("{$this->storeUrl}/wp-json/wc/v3/orders", $orderData);

    if (!$response->successful()) {
        return response()->json([
            'error' => 'Failed to create order',
            'details' => $response->body(),
        ], 500);
    }

    $order = $response->json();

    $orderId = $order['id'];
    $orderKey = $order['order_key'];

    $paymentUrl = "{$this->storeUrl}/checkout/order-pay/{$orderId}/?pay_for_order=true&key={$orderKey}";

    // return view('payment', [
    //     'payment_url' => $paymentUrl
    // ]);
    return redirect()->route('order.index', [
        'page' => 1 // Provide a default page value, e.g., 1
    ]);
}


public function products($name)
{
    // dd("yyyy");
    $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
        ->get($this->storeUrl . "/wp-json/wc/v3/products", [
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

public function order($page)
{
    $page = $page ?? 1; // Use the provided page or default to 1
    $perPage = 5; // Number of orders per page

    $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
        ->get("{$this->storeUrl}/wp-json/wc/v3/orders", [
            'per_page' => $perPage,
            'page' => $page,
            'status' => ['cancelled', 'pending', 'on-hold'] // Default to 'any' if no status is provided
        ]);

    if (!$response || !method_exists($response, 'failed') || $response->failed()) {
        return response()->json(['error' => 'Failed to fetch orders'], 500);
    }

    $orders = $response->json();

    if (empty($orders)) {
        return response()->json(['message' => 'No orders found'], 404);
    }

    return view('orders', [
        'orders' => $orders
    ]);

    // return response()->json($orders);
}
public function product(){
    dd("test");
    $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
        ->get($this->storeUrl . "/wp-json/wc/v3/products", [
            'status' => 'publish', // Filter to get only published products
            'per_page' => 100
        ]);

    if ($response->failed()) {
        return response()->json(['error' => 'Failed to fetch products'], 500);
    }

    $products = $response->json();

    if (empty($products)) {
        return response()->json(['message' => 'No products found'], 404);
    }

    // return response()->json([
    //     // 'count' => count($products),
    //     'products' => $products
    // ]);
    dd($products);

    return view('product.index', [
        'products'=> $products
    ]);
}

}
