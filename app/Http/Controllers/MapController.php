<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    public function show()
    {
        return view('order.map'); // Blade file: resources/views/map.blade.php
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query || strlen($query) < 3) {
            return response()->json(['message' => 'Query too short'], 422);
        }

        $cacheKey = 'addis_nominatim_' . md5($query);
        $results = Cache::remember($cacheKey, now()->addDay(), function () use ($query) {
            $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' .
                urlencode($query . ' Addis Ababa') .
                '&addressdetails=1&limit=5&viewbox=38.65,9.10,38.85,8.90&bounded=1';

            $response = Http::get($url);
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($results);
    }

    public function getDistance(Request $request)
    {
        $distance = $request->cookie('delivery_distance');
        return response()->json(['distance' => $distance]);
    }
}
