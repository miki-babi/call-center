<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    //
     protected $table = 'delivery_price';
    use HasFactory;
    protected $fillable=[
        'base_price',
        'price_per_km',
        'max_weight',
        'max_distance',
        'mode_of_delivery',
    ];
}
