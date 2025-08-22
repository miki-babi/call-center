<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;

class Chapa
{
 
    public static function initiate($order_id)
    {


        // dd('test');
        $tx_ref = Str::uuid();

        $response = Http::withHeaders([
            'Authorization' => "Bearer " . env('Chapa_Secretkey'),
            'Content-Type' => 'application/json',
        ])->post('https://api.chapa.co/v1/transaction/initialize', [
            'amount' => '10',
            'currency' => 'ETB',
            'email' => 'abebech_bekele@gmail.com',
            'first_name' => 'Bilen',
            'last_name' => 'Gizachew',
            'phone_number' => '0912345678',
            'tx_ref' => $tx_ref,
            'callback_url' => route('callback'),
            'return_url' => 'https://call-center.beshgebeya.co/chapa/verify/' . $order_id,
            'customization' => [
                'title' => 'Payment',
                'description' => ' '
            ],
            'meta' => [
                'hide_receipt' => 'true'
            ]
        ]);
            if ($response->json()['data']['checkout_url']) {
        return redirect()->away($response->json()['data']['checkout_url']);  // Laravel safe redirect
    }

        // $order_id="test";
        // return $response->json()['data']['checkout_url'] . $order_id;
    }
}