<?php

namespace App\Helpers;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;

class Chapa
{

    public static function initiate($shop, $order_id)
    {


        // $shopOrder= Payment::where('shop_id', $shop->id)->where('order_id', $order_id)->where('status', 0)->first();

        // dd('test');
        $tx_ref = Str::uuid();

        $response = Http::withHeaders([
            'Authorization' => "Bearer " . env('Chapa_Secretkey'),
            'Content-Type' => 'application/json',
        ])->post('https://api.chapa.co/v1/transaction/initialize', [
            'amount' => '10',
            'currency' => 'ETB',
            "email" => "abebech_bekele@gmail.com",
            "first_name" => "Bilen",
            "last_name" => "Gizachew",
            "phone_number" => "0912345678",
            'tx_ref' => $tx_ref,
            'callback_url' => route('callback', [
                'shop' => $shop,
                'order' => $order_id,
                'trx_ref' => $tx_ref
            ]),
            'return_url' => route('verify', [
                'trx_ref' => $tx_ref,
                'shop' => $shop,
                'order_id' => $order_id
            ]),
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

    public static function verify($trx_ref)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer " . env('Chapa_Secretkey'),
            'Content-Type' => 'application/json',
        ])->get('https://api.chapa.co/v1/transaction/verify/' . $trx_ref);

        return $response->json();
    }
}