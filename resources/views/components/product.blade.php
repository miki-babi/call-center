@props(['products', 'deliveryOptions'])

<div class="w-full">
    <div class="w-full mt-8 relative z-1000 flex flex-row gap-6">
        <!-- Product Search & List (2/3 width) -->
        <div class="w-2/3">
            <div class="bg-white text-gray-900 p-6 rounded-2xl shadow-lg border border-gray-200 relative">
                <div class="relative mb-4 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" />
                        </svg>
                    </span>
                    <input type="text" id="search-product" placeholder="Search products..."
                        class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 bg-white shadow-sm transition-all duration-150">
                </div>

                <!-- Product Results -->
                <div id="product-results"
                    class="absolute top-20 left-0 w-full max-h-[300px] overflow-y-auto bg-white rounded-xl shadow-lg border border-gray-200 z-50 hidden">
                    @foreach ($products as $shopData)
                        <div class="mb-8">
                            <ul class="space-y-4">
                                @foreach ($shopData['products'] as $product)
                                    <li
                                        class="product-item bg-white text-gray-900 rounded-xl shadow hover:shadow-lg transition-all duration-150 p-6 flex flex-col gap-2 border border-gray-100">
                                        <div class="flex flex-wrap items-center gap-4">
                                            <img src="{{ $product['images'][0]['src'] ?? '' }}"
                                                alt="{{ $product['name'] }}" class="w-16 h-16 object-cover rounded" />
                                            <div>
                                                <h3 class="text-lg font-semibold">{{ $product['name'] }}</h3>
                                                <p class="text-sm text-gray-700">Price:
                                                    {{ $product['price'] ? $product['price'] . ' ' . ($product['currency'] ?? 'ETB') : 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-500" id="weight">
                                                    {{ Str::limit($product['weight'] ?? '', 100) }}</p>
                                            </div>
                                        </div>
                                        <button
                                            class="toggle-cart bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded text-sm"
                                            data-product-id="{{ $product['id'] }}"
                                            data-product-weight="{{ $product['weight'] }}">
                                            Add to Cart (weight: {{ $product['weight'] }})
                                        </button>
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
        </div>

        <!-- Cart (1/3 width) -->
        <div class="w-1/3">
            <div id="cart-summary" class="bg-gray-100 p-4 rounded-lg shadow-lg hidden text-black">
                <h2 class="text-lg font-bold mb-4">Cart</h2>
                <ul id="cart-items" class="space-y-2"></ul>
                <div class="mt-4 text-right font-semibold text-lg">
                    <p>Total: <span id="cart-total">0</span> ETB</p>
                    <p>Total Weight: <span id="cart-weight">0.00</span> kg</p>
                    <p>Delivery: <span id="delivery-cost">0</span> ETB</p>
                </div>


            </div>
        </div>
    </div>
   


    <script>
        const searchInput = document.getElementById('search-product');
        const productItems = document.querySelectorAll('.product-item');
        const shopBlocks = document.querySelectorAll('.mb-8');
        const resultsBox = document.getElementById('product-results');

        // Hide results on load
        resultsBox.style.display = 'none';
        productItems.forEach(item => item.style.display = 'none');
        shopBlocks.forEach(block => block.style.display = 'none');

        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase().trim();

            if (searchValue === '') {
                productItems.forEach(item => item.style.display = 'none');
                shopBlocks.forEach(block => block.style.display = 'none');
                resultsBox.style.display = 'none';
                return;
            }

            let hasMatch = false;
            shopBlocks.forEach(shopBlock => {
                let matchFound = false;
                const items = shopBlock.querySelectorAll('.product-item');

                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    const isMatch = text.includes(searchValue);
                    item.style.display = isMatch ? 'flex' : 'none';
                    if (isMatch) matchFound = true;
                });

                shopBlock.style.display = matchFound ? 'block' : 'none';
                if (matchFound) hasMatch = true;
            });

            resultsBox.style.display = hasMatch ? 'block' : 'none';
        });
    </script>
</div>
