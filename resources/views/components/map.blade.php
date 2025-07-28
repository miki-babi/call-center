@props(['shop', 'lat', 'lon', 'deliveryOptions'])

<div class="search-box w-full justify-end flex pr-4 mt-8">
    <input type="text" id="search" placeholder="Search location..." style="width: 300px; padding: 6px;">
    <div id="results" class="search-results"></div>
</div>

<div id="map"></div>
<div class="distance-display" id="distance"></div>
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
        if (window.updateDistanceAndDelivery) {
            window.updateDistanceAndDelivery(lat, lon);
        }

        endMarker.on('dragend', function(e) {
            const newLatLng = e.target.getLatLng();
            calculateDistance(newLatLng.lat, newLatLng.lng);
            if (window.updateDistanceAndDelivery) {
                window.updateDistanceAndDelivery(newLatLng.lat, newLatLng.lng);
            }
        });

        if (result) fillAddressFields(result);
    }

    // function calculateDistance(lat, lon) {
    //     if (!startMarker) {
    //         startMarker = L.marker([{{ $lat }}, {{ $lon }}], {
    //                 color: 'green'
    //             })
    //             .addTo(map)
    //             .bindPopup("{{ $shop }}")
    //             .openPopup();
    //     }

    //     const from = startMarker.getLatLng();
    //     const to = L.latLng(lat, lon);
    //     const distanceKm = from.distanceTo(to) / 1000;

    //     distanceDiv.textContent = `Distance: ${distanceKm.toFixed(2)} km`;
    //     window.currentDistance = distanceKm;
    //     if (typeof window.renderCart === 'function') {
    //         window.renderCart();
    //     }

    // }

    // window.calculateDeliveryPrice = function(weight, distance) {
    //     const option = window.deliveryOptions.find(opt =>
    //         weight <= parseFloat(opt.max_weight) &&
    //         distance <= parseFloat(opt.max_distance)
    //     );
    //     if (!option) return 0;
    //     return parseFloat(option.base_price) + distance * parseFloat(option.price_per_km);
    // };

    // function calculateDistance(lat, lon) {
    //     if (!startMarker) {
    //         startMarker = L.marker([{{ $lat }}, {{ $lon }}], {
    //                 color: 'green'
    //             })
    //             .addTo(map)
    //             .bindPopup("{{ $shop }}")
    //             .openPopup();
    //     }

    //     const from = startMarker.getLatLng();
    //     const to = L.latLng(lat, lon);
    //     const distanceKm = from.distanceTo(to) / 1000;

    //     distanceDiv.textContent = `Distance: ${distanceKm.toFixed(2)} km`;
    //     window.currentDistance = distanceKm;

    //     // ✅ Call calculateDeliveryPrice using global cartWeight
    //     // if (typeof window.calculateDeliveryPrice === 'function' && typeof window.cartWeight !== 'undefined') {
    //     //     console.log("cart weigth :", window.cartWeight);

    //     //     const cost = window.calculateDeliveryPrice(window.cartWeight, window.currentDistance);
    //     //     console.log("Updated delivery cost:", cost);
    //     // }
    //     if (typeof window.calculateDeliveryPrice === 'function' && typeof window.cartWeight !== 'undefined') {
    //         const cost = window.calculateDeliveryPrice(window.cartWeight, distanceKm);
    //         window.deliveryCost = cost; // 👈 make it global
    //     }


    //     // ✅ Then update cart
    //     if (typeof window.renderCart === 'function') {
    //         window.renderCart();
    //     }
    // }


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

    // Expose updateDistanceAndDelivery globally for cart/delivery calculation
    // window.updateDistanceAndDelivery = function(lat, lon) {
    //     if (typeof currentDistance === 'undefined') return;
    //     if (typeof renderCart !== 'function') return;
    //     if (!window.startMarker) return;
    //     const from = window.startMarker.getLatLng();
    //     const to = L.latLng(lat, lon);
    //     const distanceKm = from.distanceTo(to) / 1000;
    //     currentDistance = distanceKm;
    //     renderCart();
    // };

<script>
    // 🌍 Global variables
    window.cart = {};
    window.cartWeight = 0;
    window.currentDistance = 0;
    window.deliveryCost = 0;

    // 🧮 Price formatter
    function formatPrice(price) {
        return `${parseFloat(price).toFixed(2)} Br`;
    }

    // 🚚 Delivery price calculator
    window.calculateDeliveryPrice = function(weight, distance) {
        const option = window.deliveryOptions.find(opt =>
            weight <= parseFloat(opt.max_weight) &&
            distance <= parseFloat(opt.max_distance)
        );
        if (!option) return 0;
        return parseFloat(option.base_price) + distance * parseFloat(option.price_per_km);
    };

    // 📍 Distance calculator (requires Leaflet and startMarker to exist)
    function calculateDistance(lat, lon) {
        if (!window.startMarker) {
            window.startMarker = L.marker([{{ $lat }}, {{ $lon }}], { color: 'green' })
                .addTo(map)
                .bindPopup("{{ $shop }}")
                .openPopup();
        }

        const from = window.startMarker.getLatLng();
        const to = L.latLng(lat, lon);
        const distanceKm = from.distanceTo(to) / 1000;

        document.getElementById('distance').textContent = `Distance: ${distanceKm.toFixed(2)} km`;
        window.currentDistance = distanceKm;

        // Update delivery cost using current cart weight
        if (typeof window.cartWeight !== 'undefined') {
            window.deliveryCost = window.calculateDeliveryPrice(window.cartWeight, distanceKm);
        }

        if (typeof window.renderCart === 'function') {
            window.renderCart();
        }
    }

    // 🛒 Cart renderer
    window.renderCart = function() {
        const cartItemsContainer = document.getElementById('cart-items');
        const cartSummary = document.getElementById('cart-summary');
        const totalElement = document.getElementById('cart-total');
        const deliveryElement = document.getElementById('delivery-cost');
        const weightElement = document.getElementById('cart-weight');

        cartItemsContainer.innerHTML = '';
        let total = 0;
        let totalWeight = 0;
        let hasItems = false;

        for (const id in window.cart) {
            const item = window.cart[id];
            total += item.price * item.quantity;
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

        window.cartWeight = totalWeight;

        deliveryElement.innerText = formatPrice(window.deliveryCost || 0);
        totalElement.innerText = formatPrice(total + (window.deliveryCost || 0));
        if (weightElement) weightElement.innerText = `${totalWeight.toFixed(2)} kg`;

        const deliveryInput = document.getElementById('delivery_price');
        const productsInput = document.getElementById('products');
        if (deliveryInput) deliveryInput.value = window.deliveryCost;
        if (productsInput) productsInput.value = JSON.stringify(Object.values(window.cart));

        cartSummary.style.display = hasItems ? 'block' : 'none';

        document.querySelectorAll('.increase').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                window.cart[id].quantity += 1;
                window.renderCart();
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
                window.renderCart();
                updateToggleButtons();
            });
        });
    };
</script>
</script>
