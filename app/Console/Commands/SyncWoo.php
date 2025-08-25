<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Shop;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;


class SyncWoo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-woo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $shops = Shop::getCachedAllShops();
        foreach ($shops as $shop) {
            Log::channel('syncwoo')->info('Syncing orders for shop: ' . $shop->name);

            $cacheKey = "woo_orders_{$shop->id}";

            $orders = Cache::remember($cacheKey, now()->addSeconds(3), function () use ($shop) {
                return retry(5, function () use ($shop) {

                    $response = Http::withBasicAuth($shop->consumer_key, $shop->consumer_secret)
                        ->timeout(80)
                        ->get($shop->url . '/wp-json/wc/v3/orders', [
                            'before' => Carbon::now()->toIso8601String(),
                            'orderby' => 'date',
                        ]);

                    if ($response->successful()) {
                        return $response->json();
                    }

                    Log::channel('syncwoo')->error("Failed to fetch orders for shop: {$shop->name}, status: " . $response->status());
                    return []; // fallback on failed response
                }, 5000, function ($exception) use ($shop) {
                    if (Str::contains($exception->getMessage(), 'cURL error 28')) {
                        Log::channel('syncwoo')->warning("Timeout fetching orders for shop: {$shop->name}, retrying...");
                        return true;
                    }
                    return false;
                });
            });

            if (count($orders) > 0) {
                foreach ($orders as $i => $order) {
                    Log::channel('syncwoo')->info("----- Order #{$i} -----");
                    Log::channel('syncwoo')->info('Order ID: ' . $order['id']);
                    Log::channel('syncwoo')->info('Customer ID: ' . $order['customer_id']);
                    Log::channel('syncwoo')->info('Order Number: ' . $order['number']);
                    Log::channel('syncwoo')->info('Order Date: ' . $order['date_created']);
                    Log::channel('syncwoo')->info('Total Amount: ' . $order['total']);
                    Log::channel('syncwoo')->info('Order Status: ' . $order['status']);
                    Log::channel('syncwoo')->info('Payment Method: ' . $order['payment_method']);
                    Log::channel('syncwoo')->info('Tracking Number: ' . ($order['tracking_number'] ?? ''));
                    Log::channel('syncwoo')->info('Customer Note: ' . ($order['customer_note'] ?? ''));
                    Log::channel('syncwoo')->info('Refund Status: ' . ($order['refund_status'] ?? 'none'));
                }

                $lastOrderId = end($orders)['id'] ?? null;
                Log::channel('syncwoo')->info('Last Order ID: ' . $lastOrderId);
            }
        }
    }
}
