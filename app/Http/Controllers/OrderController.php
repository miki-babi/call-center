<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Delivery;

class OrderController extends Controller
{
    //
    public function index()
    {
        // Logic to list orders
        return view('order.index');
    }
    public function newOrder()
    {
        // Logic to list orders

        return view('order.newOrder');
    }


    public function branch($branch)
    {
        $shop = Shop::where('name', $branch)->first();
        // dd($shop);
        if ($shop->name == 'mexico') {
            $cacheKey = "woo_products_{$shop->id}";

            $products = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($shop) {
                $allProducts = [];
                $page = 1;
                $perPage = 100;

                do {
                    $response = Http::withBasicAuth($shop->consumer_key, $shop->consumer_secret)
                        ->timeout(80)
                        ->get($shop->url . '/wp-json/wc/v3/products', [
                            'per_page' => $perPage,
                            'page' => $page,
                        ]);

                    if (!$response->successful()) {
                        Log::error("Failed fetching products on page {$page} for shop {$shop->name}");
                        break;
                    }

                    $productsPage = $response->json();

                    foreach ($productsPage as &$product) {
                        if (!empty($product['images'])) {
                            foreach ($product['images'] as &$image) {
                                $imageUrl = $image['src'] ?? null;
                                if ($imageUrl) {
                                    $imageName = basename(parse_url($imageUrl, PHP_URL_PATH));
                                    $folder = public_path("products/{$shop->id}");

                                    if (!file_exists($folder)) {
                                        mkdir($folder, 0755, true);
                                    }

                                    $localImagePath = "{$folder}/{$imageName}";

                                    if (!file_exists($localImagePath)) {
                                        try {
                                            $imageContents = Http::withHeaders([
                                                'User-Agent' => 'Mozilla/5.0',
                                            ])->timeout(30)->get($imageUrl)->body();

                                            file_put_contents($localImagePath, $imageContents);
                                        } catch (\Exception $e) {
                                            Log::warning("Failed to download image {$imageUrl} for shop {$shop->name}: " . $e->getMessage());
                                            continue;
                                        }
                                    }

                                    $image['src'] = asset("products/{$shop->id}/{$imageName}");
                                }
                            }
                        }
                    }

                    $allProducts = array_merge($allProducts, $productsPage);
                    $page++;
                } while (count($productsPage) === $perPage);

                return $allProducts;
            });

            $products = collect($products)->where('status', 'publish')->values();

            if ($products->isEmpty()) {
                return response()->json(['message' => 'No Products found'], 404);
            }
            $deliveryOptions = Delivery::all();

            return view('order.new-mexico', [
                'allProducts' => [
                    [
                        'shop' => $shop->name,
                        'shop_id' => $shop->id,
                        'products' => $products,
                    ]
                ],
                'deliveryOptions' => $deliveryOptions
            ]);
        } elseif ($shop->name == 'ayat') {
            # code...
            $cacheKey = "woo_products_{$shop->id}";

            $products = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($shop) {
                $allProducts = [];
                $page = 1;
                $perPage = 100;

                do {
                    $response = Http::withBasicAuth($shop->consumer_key, $shop->consumer_secret)
                        ->timeout(80)
                        ->get($shop->url . '/wp-json/wc/v3/products', [
                            'per_page' => $perPage,
                            'page' => $page,
                        ]);

                    if (!$response->successful()) {
                        Log::error("Failed fetching products on page {$page} for shop {$shop->name}");
                        break;
                    }

                    $productsPage = $response->json();

                    foreach ($productsPage as &$product) {
                        if (!empty($product['images'])) {
                            foreach ($product['images'] as &$image) {
                                $imageUrl = $image['src'] ?? null;
                                if ($imageUrl) {
                                    $imageName = basename(parse_url($imageUrl, PHP_URL_PATH));
                                    $folder = public_path("products/{$shop->id}");

                                    if (!file_exists($folder)) {
                                        mkdir($folder, 0755, true);
                                    }

                                    $localImagePath = "{$folder}/{$imageName}";

                                    if (!file_exists($localImagePath)) {
                                        try {
                                            $imageContents = Http::withHeaders([
                                                'User-Agent' => 'Mozilla/5.0',
                                            ])->timeout(30)->get($imageUrl)->body();

                                            file_put_contents($localImagePath, $imageContents);
                                        } catch (\Exception $e) {
                                            Log::warning("Failed to download image {$imageUrl} for shop {$shop->name}: " . $e->getMessage());
                                            continue;
                                        }
                                    }

                                    $image['src'] = asset("products/{$shop->id}/{$imageName}");
                                }
                            }
                        }
                    }

                    $allProducts = array_merge($allProducts, $productsPage);
                    $page++;
                } while (count($productsPage) === $perPage);

                return $allProducts;
            });

            $products = collect($products)->where('status', 'publish')->values();

            if ($products->isEmpty()) {
                return response()->json(['message' => 'No Products found'], 404);
            }
            $deliveryOptions = Delivery::all();
            return view('order.new-ayat', [
                'allProducts' => [
                    [
                        'shop' => $shop->name,
                        'shop_id' => $shop->id,
                        'products' => $products,
                    ]
                ],
                'deliveryOptions' => $deliveryOptions
            ]);
        } else {
            return;
        }
        // Logic to list orders
        return view('order.index');
    }

    public function fetchOrders($shopName)
    {
        // dd($shopName);
        $shop = Shop::where('name', $shopName)->first();
        // dd($shop);
        $cacheKey = "woo_orders_{$shop->id}";

        // Get from cache or fetch and cache for 5 minutes
        $orders = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($shop) {
            return retry(5, function () use ($shop) {
                $response = Http::withBasicAuth($shop->consumer_key, $shop->consumer_secret)
                    ->timeout(80)
                    ->get($shop->url . '/wp-json/wc/v3/orders', [
                        'before' => Carbon::now()->endOfMonth()->format('Y-m-d\TH:i:s'),
                        'orderby' => 'date',
                    ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error("Failed to fetch orders for shop: {$shop->name}, status: " . $response->status());
                return [];
            }, 5000, function ($exception) use ($shop) {
                if (Str::contains($exception->getMessage(), 'cURL error 28')) {
                    Log::warning("Timeout fetching orders for shop: {$shop->name}, retrying...");
                    return true;
                }
                return false;
            });
        });

        [$pendingOrders, $cancelledOrders, $onHoldOrders, $completedOrders, $failedOrders, $processingOrders, $shipmentReadyOrders] = [collect($orders)->where('status', 'pending')->values(), collect($orders)->where('status', 'cancelled')->values(), collect($orders)->where('status', 'on-hold')->values(), collect($orders)->where('status', 'completed')->values(), collect($orders)->where('status', 'failed')->values(), collect($orders)->where('status', 'processing')->values(), collect($orders)->where('status', 'shipment-ready')->values()];
        return view('order.branch-order', [
            'pendingOrders' => $pendingOrders,
            'cancelledOrders' => $cancelledOrders,
            'onHoldOrders' => $onHoldOrders,
            'completedOrders' => $completedOrders,
            'failedOrders' => $failedOrders,
            'processingOrders' => $processingOrders,
            'shipmentReadyOrders' => $shipmentReadyOrders
        ]);
    }

    public function fetchAllOrders()
    {
        $allShops = Shop::getCachedAllShops();
        $allOrders = [];

        foreach ($allShops as $shop) {
            $cacheKey = "woo_orders_{$shop->id}";

            $orders = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($shop) {
                return retry(5, function () use ($shop) {
                    $response = Http::withBasicAuth($shop->consumer_key, $shop->consumer_secret)
                        ->timeout(80)
                        ->get($shop->url . '/wp-json/wc/v3/orders', [
                            'before' => Carbon::now()->endOfMonth()->format('Y-m-d\TH:i:s'),
                            'orderby' => 'date',
                        ]);

                    if ($response->successful()) {
                        return $response->json();  // <--- you need this!
                    }

                    Log::error("Failed to fetch orders for shop: {$shop->name}, status: " . $response->status());
                    return [];
                }, 5000, function ($exception) use ($shop) {
                    if (Str::contains($exception->getMessage(), 'cURL error 28')) {
                        Log::warning("Timeout fetching orders for shop: {$shop->name}, retrying...");
                        return true;
                    }
                    return false;
                });
            });
            // dd($orders);
            $orders = collect($orders)->values();
            $now = Carbon::now();

            $orderToday = $orders->filter(fn($order) => Carbon::parse($order['date_created'])->isSameDay($now))->values();
            $orderWeek = $orders->filter(fn($order) => Carbon::parse($order['date_created'])->between($now->startOfWeek(), $now->endOfWeek()))->values();
            $orderMonth = $orders->filter(fn($order) => Carbon::parse($order['date_created'])->isSameMonth($now))->values();

            // dd($orderToday, $orderWeek, $orderMonth);
            if ($orders->isNotEmpty()) {
                $allOrders[] = [
                    'shop' => $shop->name,
                    'orders' => $orders,
                    'orders_today' => $orderToday,
                    'orders_week' => $orderWeek,
                    'orders_month' => $orderMonth,
                ];
            }
        }

        if (empty($allOrders)) {
            return response()->json(['message' => 'No shipment-ready orders found'], 404);
        }

        return view('order.index', [
            'allOrders' => $allOrders,
        ]);
    }

    public function placeOrder(Request $request)
    {
        $data = $request->all();
        // dd($data);
        // dd($data['branch']);
        $deliveryPrice = $data['delivery_price'] ?? 0;
        $products = json_decode($data['products'] ?? '[]', true);
        $deliveryMethod = $data['delivery_method'] ?? 'standard_delivery';
        
        // Map delivery method to method title
        $methodTitles = [
            'standard_delivery' => 'Standard Delivery',
            'next_day_delivery' => 'Next Day Delivery',
            'express_delivery' => 'Express Delivery'
        ];
        $methodTitle = $methodTitles[$deliveryMethod] ?? 'Standard Delivery';
        
        // dd(['delivery_price' => $deliveryPrice, 'products' => $products]);

        // Prepare WooCommerce order payload
        $orderPayload = [
            'payment_method' => 'cod', // or as needed
            'payment_method_title' => 'Cash on Delivery',
            'set_paid' => false,
            'billing' => [
                'first_name' => $data['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'address_1' => $data['address_1'] ?? '',
                'city' => $data['city'] ?? '',
                'state' => $data['state'] ?? '',
                'postcode' => $data['postcode'] ?? '',
                'country' => $data['country'] ?? '',
                'email' => $data['email'] ?? '',
                'phone' => $data['phone'] ?? '',
            ],
            'shipping' => [
                'first_name' => $data['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'address_1' => $data['address_1'] ?? '',
                'city' => $data['city'] ?? '',
                'state' => $data['state'] ?? '',
                'postcode' => $data['postcode'] ?? '',
                'country' => $data['country'] ?? '',
            ],
            'line_items' => array_map(function ($item) {
                return [
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ];
            }, $products),
            'shipping_lines' => [
                [
                    'method_title' => $methodTitle,
                    'method_id' => $deliveryMethod,
                    'instance_id' => '0',
                    'total' => (string) $deliveryPrice,
                    'total_tax' => '0.00',
                    'taxes' => [],
                    'tax_status' => 'taxable',
                    'meta_data' => []
                ]
            ],
            'fee_lines' => [
                [
                    'name' => 'Delivery',
                    'total' => (string) $deliveryPrice,
                ]
            ],
        ];

        // Send to WooCommerce (assume 'mexico' shop for now)
        $shop = Shop::where('name', $data['branch'])->first();
        $response = \Illuminate\Support\Facades\Http::withBasicAuth($shop->consumer_key, $shop->consumer_secret)
            ->post($shop->url . '/wp-json/wc/v3/orders', $orderPayload);

        if ($response->successful()) {
            $orderData = $response->json();
            $paymentUrl = $orderData['payment_url'] ?? null;
            $orderNumber = $orderData['number'] ?? null;
            $totalAmount = $orderData['total'] ?? null;
            return view('order.payment', [
                'payment_url' => $paymentUrl,
                'order_number' => $orderNumber,
                'total_amount' => $totalAmount,
            ]);
        } else {
            return redirect()->back()->with('error', 'Failed to place order.');
        }
    }
}
