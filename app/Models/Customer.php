<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'woo_customer_id',
        'email',
        'phone',
        'billing_address',
        'shipping_address',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
