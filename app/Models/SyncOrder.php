<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncOrder extends Model
{
    //
    protected $fillable = [
        'shop_id',
        'sync_status',
        'last_synced_at',
        'status',
    ];
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
