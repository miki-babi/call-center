<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = [
        'woo_customer_id',
        'shop_id',
        'order-id',
        'status',
        'order_date',
        'total_amount',
        'payment_method',
        'payment_status',
        'customer_note',
        'refund_status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
