@extends('layout.app')

@section('content')
<div class="w-full mx-auto mt-8">
    <div class="flex flex-col gap-4">
        <div class="bg-white text-gray-900 p-6 rounded-2xl shadow-lg border border-gray-200 mt-4">
            <div class="relative mb-4 max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                    </svg>
                </span>
                <input type="text" id="search" placeholder="Search products..."
                    class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 bg-white shadow-sm transition-all duration-150">
            </div>
            <h1 class="text-3xl font-extrabold mb-6 text-blue-700">Products</h1>
            <p class="mb-6 text-gray-500">Here are the products from all shops:</p>

            @foreach ($allProducts as $shopData)
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4 px-2 py-1 bg-blue-50 rounded-lg inline-block border border-blue-100 shadow-sm">
                        {{ $shopData['shop'] }}
                    </h2>
                    <ul class="space-y-4">
                        @foreach ($shopData['products'] as $product)
                            <li class="product-item bg-white text-gray-900 rounded-xl shadow hover:shadow-lg transition-all duration-150 p-6 flex flex-col gap-2 border border-gray-100">
                                <div class="flex flex-wrap items-center gap-4">
                                    <img src="{{ $product['images'][0]['src'] ?? '' }}" alt="{{ $product['name'] }}" class="w-16 h-16 object-cover rounded" />
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ $product['name'] }}</h3>
                                        <p class="text-sm text-gray-700">Price: {{ $product['price'] ? $product['price'] . ' ' . ($product['currency'] ?? 'ETB') : 'N/A' }}</p>
                                        <p class="text-sm text-gray-500">{{ Str::limit($product['description'] ?? '', 100) }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if (!$loop->last)
                    <hr class="my-8 border-t border-gray-200">
                @endif
            @endforeach
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const listItems = document.querySelectorAll('.product-item');

        searchInput.addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            listItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchValue) ? 'flex' : 'none';
            });
        });
    </script>
</div>
@endsection
