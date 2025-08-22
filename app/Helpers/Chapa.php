<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Chapa
{
 
    public static function initiate()
    {
        // dd('test');
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
            'tx_ref' => 'chewatatest-6669',
            'callback_url' => 'https://webhook.site/077164d6-29cb-40df-ba29-8a00e59a7e60',
            'return_url' => 'https://www.google.com/',
            'customization' => [
                'title' => 'Payment',
                'description' => ' '
            ],
            'meta' => [
                'hide_receipt' => 'true'
            ]
        ]);

        return $response->body();
    }
}