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
            <a" href="{{ route('orders.index') }}">Orders</a>
        </div>
    </nav>

    <div class="flex h-screen">
        <!-- Main Content -->
        <main class="flex-1 p-4 ">
            @yield('content')
        </main>
    </div>


    {{-- <script>
        window.cart = window.cart || {};
        window.currentDistance = window.currentDistance || 0;

        const deliveryOptions = @json($deliveryOptions);
        // const deliveryOptions = "test";
        console.log(deliveryOptions);
        // Pass from backend

        function formatPrice(price) {
            return parseFloat(price).toFixed(2);
        }

        function calculateDeliveryPrice(weight) {
            const option = deliveryOptions.find(opt => weight <= parseFloat(opt.max_weight));
            if (!option || !window.currentDistance) return 0;

            const base = parseFloat(option.base_price);
            const perKm = parseFloat(option.price_per_km);
            const maxDist = parseFloat(option.max_distance);

            if (window.currentDistance > maxDist) return 0;
            return base + (perKm * window.currentDistance);
        }
        window.calculateDeliveryPrice = calculateDeliveryPrice;

        function renderCart() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartSummary = document.getElementById('cart-summary');
            const totalElement = document.getElementById('cart-total');
            const deliveryElement = document.getElementById('delivery-cost');
            const weightElement = document.getElementById('cart-weight');


            console.log("helllo");
            

            cartItemsContainer.innerHTML = '';
            let total = 0,
                totalWeight = 0,
                hasItems = false;

            for (const id in window.cart) {
                const item = window.cart[id];
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

            // 👇 Expose totalWeight globally
            window.cartWeight = totalWeight;

            const deliveryCost = window.deliveryCost || 0;


            deliveryElement.innerText = formatPrice(deliveryCost);
            totalElement.innerText = formatPrice(total + deliveryCost);
            if (weightElement) weightElement.innerText = `${totalWeight.toFixed(2)} kg`;

            // Update hidden fields for form submission
            const deliveryPriceInput = document.getElementById('delivery_price');
            const productsInput = document.getElementById('products');
            if (deliveryPriceInput) deliveryPriceInput.value = deliveryCost;
            if (productsInput) productsInput.value = JSON.stringify(Object.values(window.cart));

            cartSummary.style.display = hasItems ? 'block' : 'none';

            document.querySelectorAll('.increase').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    window.cart[id].quantity += 1;
                    renderCart();
                });
            });

            document.querySelectorAll('.decrease').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    if (window.cart[id].quantity > 1) {
                        window.cart[id].quantity -= 1;
                    } else {
                        delete window.cart[id];
                    }
                    renderCart();
                    updateToggleButtons();
                });
            });
        }

        function updateToggleButtons() {
            document.querySelectorAll('.toggle-cart').forEach(button => {
                const id = button.dataset.productId;
                const isInCart = window.cart[id];
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
                const weight = parseFloat(this.getAttribute('data-product-weight')) || 0;

                if (!window.cart[id]) {
                    window.cart[id] = {
                        id,
                        name,
                        price,
                        weight,
                        quantity: 1
                    };
                } else {
                    delete window.cart[id];
                }

                updateToggleButtons();
                renderCart();
            });
        });

    </script> --}}




</body>

</html>
