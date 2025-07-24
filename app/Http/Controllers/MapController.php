<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class MapController extends Controller
{
    public function show()
    {
        return view('order.map');
    }


public function search(Request $request)
{
    $query = $request->query('query');
    Log::info('Search query received: ' . $query);

    if (!$query || strlen($query) < 3) {
        Log::info('Query too short or empty');
        return response()->json([]);
    }

    $cacheKey = 'addis_search_' . md5($query);
    $results = Cache::remember($cacheKey, 86400, function () use ($query) {
        $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' .
            urlencode($query . ' Addis Ababa') .
            '&addressdetails=1&limit=5';

        $response = Http::withHeaders([
            'User-Agent' => 'MyLaravelApp (myemail@example.com)'
        ])->get($url);

        Log::info('Raw response: ' . $response->body());

        return $response->successful() ? $response->json() : [];
    });

    Log::info('Returning ' . count($results) . ' results');
    return response()->json($results);
}

}
