<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'phone',
        'amount',
        'tx_ref',
        'callback_url',
        'order_id',
        'status',
    ];

}
