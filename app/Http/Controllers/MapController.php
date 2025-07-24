<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MapController extends Controller
{
    public function show()
    {
        return view('map');
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query || strlen($query) < 3) {
            return response()->json([]);
        }

        $cacheKey = 'addis_search_' . md5($query);
        $results = Cache::remember($cacheKey, 86400, function () use ($query) {
            $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' .
                urlencode($query . ' Addis Ababa') .
                '&addressdetails=1&limit=5&viewbox=38.65,9.10,38.85,8.90&bounded=1';

            $response = Http::get($url);
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($results);
    }
}
