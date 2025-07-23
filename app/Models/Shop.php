<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Shop extends Model
{
    /** @use HasFactory<\Database\Factories\ShopFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'consumer_key',
        'consumer_secret',
        'url',
    ];
   protected static function booted()
    {
        static::saved(function ($shop) {
            Cache::put("shop_{$shop->name}", $shop, 3600);
            Cache::forget('all_shops'); // Clear the full list cache so it refreshes
        });

        static::deleted(function ($shop) {
            Cache::forget("shop_{$shop->name}");
            Cache::forget('all_shops'); // Clear the full list cache so it refreshes
        });
    }

    public static function getCachedAllShops()
    {
        return Cache::remember('all_shops', 3600, function () {
            return self::all();
        });
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function syncOrders()
    {
        return $this->hasMany(SyncOrder::class);
    }
}

