<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Farmer Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
     <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        #map {
            margin-top: 20px;
            /* space between search/results and map */
            height: 500px;
            width: 100%;
            /* z-index: -10; */
        }

        .search-box {
            margin: 10px;
            padding-bottom: 10px;
            margin-top: 10px;
        }

        .search-results {
            margin-top: 35px;
            background: #fff;
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            color: #000;
            font-weight: bold;
            position: absolute;
            z-index: 1000;
            width: 300px;
        }

        .search-results div {
            margin-top: 5px;
            padding: 5px;
            cursor: pointer;
        }

        .search-results div:hover {
            background: #eee;
        }

        .distance-display {
            margin: 10px;
            font-weight: bold;
        }

        .product-item,
        .mb-8 {
            display: none;
        }
    </style>
</head>

<body x-data="{ loading: false }" x-init="loading = true;
setTimeout(() => loading = false, 1000)">
    <div x-show="loading" class="fixed inset-0  bg-opacity-75 flex items-center justify-center z-50">
        <!-- Your SVG here -->
        <svg class="w-12 h-12 animate-spin text-white-800" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
    </div>

    <nav class="bg-gray-800 text-white p-4 flex justify-between items-center">

        <div role="tablist" class="tabs tabs-border items-center">
            <div class="text-lg font-semibold">
                <span>Current branch: </span>
            </div>
            <a role="tab" class="tab {{ request()->is('order/new/mexico') ? 'tab-active' : '' }}"
                href="{{ route('orders.new.branch', ['branch' => 'mexico']) }}">Mexico</a>

            <a role="tab" class="tab {{ request()->is('order/new/ayat') ? 'tab-active' : '' }}"
                href="{{ route('orders.new.branch', ['branch' => 'ayat']) }}">Ayat</a>
            <a role="tab" class="tab tab-disabled">Kadisco</a>
        </div>
        <div class="p-4 bg-blue-500 text-white rounded-sm ">
            <a"
                href="{{ route('orders.index') }}">Orders</a>
        </div>
    </nav>

    <div class="flex h-screen">
        <!-- Main Content -->
        <main class="flex-1 p-4 ">
            @yield('content')
        </main>
    </div>


</body>

</html>
