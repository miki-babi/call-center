<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;



class ProductController extends Controller
{
    //


    public function fetchAllProducts()
    {
        $allShops = Shop::getCachedAllShops();
        $allProducts = [];

        foreach ($allShops as $shop) {
            $cacheKey = "woo_products_{$shop->id}";

            $products = Cache::remember($cacheKey, 3600, function () use ($shop) {
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

            if ($products->isNotEmpty()) {
                $allProducts[] = [
                    'shop' => $shop->name,
                    'shop_id' => $shop->id,
                    'products' => $products,
                ];
            }
        }

        if (empty($allProducts)) {
            return response()->json(['message' => 'No Products found'], 404);
        }

        return view('product.index', ['allProducts' => $allProducts]);
    }
 public function fetchProducts($branch)
{
    $shop=Shop::where('name',$branch)->first();
    $cacheKey = "woo_products_{$shop->id}";

    $products = Cache::remember($cacheKey,  now()->addSeconds(5), function () use ($shop) {
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

    return view('product.index', [
        'allProducts' => [
            [
                'shop' => $shop->name,
                'shop_id' => $shop->id,
                'products' => $products,
            ]
        ]
    ]);
}


}
