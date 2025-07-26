<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    //
    protected $fillable=[
        'base_price',
        'price_per_km',
        'max_weight',
        'max_distance',
        'mode_of_delivery',
    ];
}
