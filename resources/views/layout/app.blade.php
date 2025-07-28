<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Farmer Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            <a role="tab" class="tab {{ request()->is('shop/orders') ? 'tab-active' : '' }} "
                href="{{ route('orders.index') }}">All</a>
            <a role="tab" class="tab {{ request()->is('shop/orders/mexico') ? 'tab-active' : '' }} "
                href="{{ route('orders.fetch', ['name' => 'mexico']) }}">Mexico</a>
            <a role="tab" class="tab {{ request()->is('shop/orders/ayat') ? 'tab-active' : '' }} "
                href="{{ route('orders.fetch', ['name' => 'ayat']) }}">Ayat</a>
            <a role="tab" class="tab tab-disabled">Kadisco</a>
        </div>
        <div class="p-4 bg-blue-500 text-white rounded-sm ">
            <a href="{{ route('orders.new') }}">
            create new order 
            </a>
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
