@extends('layout.app')

@section('content')
    <!-- Leaflet CSS -->
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

    <body>
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
                @foreach ($allProducts as $shopData)
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
                                            <p class="text-sm text-gray-500">
                                                {{ Str::limit($product['description'] ?? '', 100) }}</p>
                                        </div>
                                    </div>
                                    <button
                                        class="toggle-cart bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded text-sm"
                                        data-product-id="{{ $product['id'] }}">
                                        Add to Cart
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
                Total: <span id="cart-total">0</span> ETB
            </div>
        </div>
    </div>
</div>

        <script>
                const cart = {}; // { productId: { id, name, price, quantity } }

                function formatPrice(price) {
                    return parseFloat(price).toFixed(2);
                }

                function renderCart() {
                    const cartItemsContainer = document.getElementById('cart-items');
                    const cartSummary = document.getElementById('cart-summary');
                    const totalElement = document.getElementById('cart-total');

                    cartItemsContainer.innerHTML = '';
                    let total = 0;
                    let hasItems = false;

                    for (const id in cart) {
                        const item = cart[id];
                        const lineTotal = item.price * item.quantity;
                        total += lineTotal;
                        hasItems = true;

                        cartItemsContainer.innerHTML += `
                <li class="flex justify-between items-center">
                    <div>
                        <div class="font-semibold">${item.name}</div>
                        <div class="text-sm text-gray-600">Price: ${formatPrice(item.price)} × ${item.quantity}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="decrease bg-gray-300 px-2 rounded" data-id="${id}">-</button>
                        <span>${item.quantity}</span>
                        <button class="increase bg-gray-300 px-2 rounded" data-id="${id}">+</button>
                    </div>
                </li>
            `;
                    }

                    totalElement.innerText = formatPrice(total);
                    cartSummary.style.display = hasItems ? 'block' : 'none';

                    // Reattach events
                    document.querySelectorAll('.increase').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const id = btn.dataset.id;
                            cart[id].quantity += 1;
                            renderCart();
                        });
                    });

                    document.querySelectorAll('.decrease').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const id = btn.dataset.id;
                            if (cart[id].quantity > 1) {
                                cart[id].quantity -= 1;
                            } else {
                                delete cart[id];
                            }
                            renderCart();
                            updateToggleButtons();
                        });
                    });
                }

                function updateToggleButtons() {
                    document.querySelectorAll('.toggle-cart').forEach(button => {
                        const id = button.dataset.productId;
                        const isInCart = cart[id];
                        button.innerText = isInCart ? 'Remove from Cart' : 'Add to Cart';
                        button.classList.toggle('bg-red-500', isInCart);
                        button.classList.toggle('bg-blue-500', !isInCart);
                    });
                }

                document.querySelectorAll('.toggle-cart').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.dataset.productId;
                        const name = this.closest('.product-item').querySelector('h3').innerText;
                        const priceText = this.closest('.product-item').querySelector('p.text-sm').innerText;
                        const price = parseFloat(priceText.replace(/[^0-9.]/g, ''));

                        if (!cart[id]) {
                            cart[id] = {
                                id,
                                name,
                                price,
                                quantity: 1
                            };
                        } else {
                            delete cart[id];
                        }

                        updateToggleButtons();
                        renderCart();
                    });
                });
            </script>



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


        <div class="search-box w-full justify-end flex pr-4 mt-8">
            <input type="text" id="search" placeholder="Search location..." style="width: 300px; padding: 6px;">
            <div id="results" class="search-results"></div>
        </div>

        <div id="map"></div>
        <div class="distance-display" id="distance"></div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">First Name:</label>
                <input type="text" name="first_name" required class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div>
                <label class="block font-medium">Last Name:</label>
                <input type="text" name="last_name" required class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div>
                <label class="block font-medium">Email:</label>
                <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div>
                <label class="block font-medium">Phone:</label>
                <input type="text" name="phone" class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div>
                <label class="block font-medium">Address:</label>
                <input type="text" name="address_1" id="address_1" required
                    class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div>
                <label class="block font-medium">City:</label>
                <input type="text" name="city" id="city" required
                    class="w-full border border-gray-300 rounded-lg p-2" value="Addis Ababa">
            </div>
            <div>
                <label class="block font-medium">State:</label>
                <input type="text" name="state" id="state" value="notset"
                    class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div>
                <label class="block font-medium">Postcode:</label>
                <input type="text" name="postcode" id="postcode" required
                    class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div>
                <label class="block font-medium">Country:</label>
                <select name="country" required class="w-full border border-gray-300 rounded-lg p-2">
                    <option value="ET" selected>Ethiopia</option>
                </select>
            </div>
        </div>


        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <script>
            let map = L.map('map').setView([9.03, 38.74], 13); // Addis Ababa center

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let startMarker = null;
            let endMarker = null;

            let timeout = null;

            const input = document.getElementById('search');
            const resultsDiv = document.getElementById('results');
            const distanceDiv = document.getElementById('distance');

            input.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(timeout);

                if (query.length < 2) {
                    resultsDiv.innerHTML = '';
                    return;
                }

                timeout = setTimeout(() => {
                    fetch(`/leaflet/search?query=${encodeURIComponent(query)}&accept-language=en`)
                        .then(res => res.json())
                        .then(data => renderResults(data))
                        .catch(err => console.error('Search error:', err));
                }, 500);
            });

            function renderResults(results) {
                resultsDiv.innerHTML = '';
                results.forEach(result => {
                    const div = document.createElement('div');
                    div.textContent = result.display_name;
                    div.classList.add('cursor-pointer', 'hover:bg-gray-100', 'p-1');

                    div.addEventListener('click', () => {
                        const lat = parseFloat(result.lat);
                        const lon = parseFloat(result.lon);

                        placeEndMarker(lat, lon, result);
                        resultsDiv.innerHTML = '';
                    });

                    resultsDiv.appendChild(div);
                });
            }

            function placeEndMarker(lat, lon, result = null) {
                if (endMarker) map.removeLayer(endMarker);

                endMarker = L.marker([lat, lon], {
                        draggable: true
                    }).addTo(map)
                    .bindPopup(result?.display_name || 'Selected Location')
                    .openPopup();

                map.setView([lat, lon], 15);
                calculateDistance(lat, lon);

                endMarker.on('dragend', function(e) {
                    const newLatLng = e.target.getLatLng();
                    calculateDistance(newLatLng.lat, newLatLng.lng);
                });

                if (result) fillAddressFields(result);
            }

            function calculateDistance(lat, lon) {
                if (!startMarker) {
                    startMarker = L.marker([9.03, 38.74], {
                            color: 'green'
                        })
                        .addTo(map)
                        .bindPopup('Mexico-Store')
                        .openPopup();
                }

                const from = startMarker.getLatLng();
                const to = L.latLng(lat, lon);
                const distanceKm = from.distanceTo(to) / 1000;

                distanceDiv.textContent = `Distance: ${distanceKm.toFixed(2)} km`;
            }

            function fillAddressFields(result) {
                const address = result.address || {};

                const addressInput = document.getElementById('address_1');
                const cityInput = document.getElementById('city');
                const stateInput = document.getElementById('state');
                const postcodeInput = document.getElementById('postcode');
                const countrySelect = document.querySelector('select[name="country"]');

                if (addressInput) addressInput.value = result.display_name || '';
                if (cityInput) cityInput.value = address.state || address.town || address.village || '';
                if (stateInput) stateInput.value = address.suburb || '';
                if (postcodeInput) postcodeInput.value = address.postcode || '';
                if (countrySelect && address.country_code) {
                    countrySelect.value = address.country_code.toUpperCase();
                }
            }


            // Allow placing marker by double-clicking map
            map.on('dblclick', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                placeEndMarker(lat, lng);
            });
        </script>
    @endsection
