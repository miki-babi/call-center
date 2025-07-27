@props(['products','deliveryOptions'])

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
                    <p>Total: <span id="cart-total">0</span> ETB</p>
                    <p>Total Weight: <span id="cart-weight">0.00</span> kg</p>
                    <p>Delivery: <span id="delivery-cost">0</span> ETB</p>
                </div>


            </div>
        </div>
    </div>

    {{-- <script>
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
    </script> --}}
    {{-- <script>
        const cart = {}; // { productId: { id, name, price, quantity, weight } }

        function formatPrice(price) {
            return parseFloat(price).toFixed(2);
        }

        function renderCart() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartSummary = document.getElementById('cart-summary');
            const totalElement = document.getElementById('cart-total');
            const weightElement = document.getElementById('cart-weight'); // Add this in your HTML

            cartItemsContainer.innerHTML = '';
            let total = 0;
            let totalWeight = 0;
            let hasItems = false;

            for (const id in cart) {
                const item = cart[id];
                const lineTotal = item.price * item.quantity;
                const lineWeight = item.weight * item.quantity;
                total += lineTotal;
                totalWeight += lineWeight;
                hasItems = true;

                cartItemsContainer.innerHTML += `
                <li class="flex justify-between items-center">
                    <div>
                        <div class="font-semibold">${item.name}</div>
                        <div class="text-sm text-gray-600">
                            Price: ${formatPrice(item.price)} × ${item.quantity}<br>
                            Weight: ${formatPrice(item.weight)} kg × ${item.quantity}
                        </div>
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
            if (weightElement) weightElement.innerText = `${totalWeight.toFixed(2)} kg`;
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
                const weight = parseFloat(this.dataset.weight) || 0;

                if (!cart[id]) {
                    cart[id] = {
                        id,
                        name,
                        price,
                        weight,
                        quantity: 1
                    };
                } else {
                    delete cart[id];
                }

                updateToggleButtons();
                renderCart();
            });
        });
    </script> --}}
<script>
    const cart = {}; // { productId: { id, name, price, quantity, weight } }

    const deliveryOptions = @json($deliveryOptions); // Pass from backend
    let currentDistance = 0;

    function formatPrice(price) {
        return parseFloat(price).toFixed(2);
    }

    function calculateDeliveryPrice(weight) {
        const option = deliveryOptions.find(opt => weight <= parseFloat(opt.max_weight));
        if (!option || !currentDistance) return 0;

        const base = parseFloat(option.base_price);
        const perKm = parseFloat(option.price_per_km);
        const maxDist = parseFloat(option.max_distance);

        if (currentDistance > maxDist) return 0;
        return base + (perKm * currentDistance);
    }

    function renderCart() {
        const cartItemsContainer = document.getElementById('cart-items');
        const cartSummary = document.getElementById('cart-summary');
        const totalElement = document.getElementById('cart-total');
        const deliveryElement = document.getElementById('delivery-cost');

        cartItemsContainer.innerHTML = '';
        let total = 0, totalWeight = 0, hasItems = false;

        for (const id in cart) {
            const item = cart[id];
            const lineTotal = item.price * item.quantity;
            total += lineTotal;
            totalWeight += item.weight * item.quantity;
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
                </li>`;
        }

        const deliveryCost = calculateDeliveryPrice(totalWeight);
        deliveryElement.innerText = formatPrice(deliveryCost);
        totalElement.innerText = formatPrice(total + deliveryCost);

        // Update hidden fields for form submission
        const deliveryPriceInput = document.getElementById('delivery_price');
        const productsInput = document.getElementById('products');
        if (deliveryPriceInput) deliveryPriceInput.value = deliveryCost;
        if (productsInput) productsInput.value = JSON.stringify(Object.values(cart));

        cartSummary.style.display = hasItems ? 'block' : 'none';

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
        button.addEventListener('click', function () {
            const id = this.dataset.productId;
            const name = this.closest('.product-item').querySelector('h3').innerText;
            const priceText = this.closest('.product-item').querySelector('p.text-sm').innerText;
            const price = parseFloat(priceText.replace(/[^0-9.]/g, ''));
            const weight = parseFloat(this.getAttribute('data-weight')) || 0;

            if (!cart[id]) {
                cart[id] = {
                    id,
                    name,
                    price,
                    weight,
                    quantity: 1
                };
            } else {
                delete cart[id];
            }

            updateToggleButtons();
            renderCart();
        });
    });

    function updateDistanceAndDelivery(lat, lon) {
        if (!startMarker) return;
        const from = startMarker.getLatLng();
        const to = L.latLng(lat, lon);
        const distanceKm = from.distanceTo(to) / 1000;
        currentDistance = distanceKm;
        document.getElementById('distance-display').innerText = `Distance: ${distanceKm.toFixed(2)} km`;
        renderCart();
    }
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
