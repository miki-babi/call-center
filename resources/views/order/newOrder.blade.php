@extends('layout.new-order')

@section('content')
    <div>
        <div>
            <a role="tab" class="tab {{ request()->is('order/new/mexico') ? 'tab-active' : '' }}"
                href="{{ route('orders.new.branch', ['branch' => 'mexico']) }}">Mexico</a>

            <a role="tab" class="tab {{ request()->is('order/new/ayat') ? 'tab-active' : '' }}"
                href="{{ route('orders.new.branch', ['branch' => 'ayat']) }}">Ayat</a>
        </div>
    </div>


    @php
        $shop1 = ['name' => 'Ayat Store', 'lat' => 9.023810040605607, 'lon' => 38.897535922518266, 'branch' => 'ayat'];
        $shop2 = [
            'name' => 'Mexico Store',
            'lat' => 9.009907758314755,
            'lon' => 38.75883353263652,
            'branch' => 'mexico',
        ];
    @endphp

    <!-- Search input -->
    <div class="search-box w-full justify-end flex pr-4 mt-8">
        <input type="text" id="search" placeholder="Search location..." style="width: 300px; padding: 6px;"
            class="border rounded">
        <div id="results" class="search-results"></div>
    </div>

    <!-- Shop list -->
    <ul id="shop-list" class="flex gap-4 mt-4">
        <a role="tab" class="p-4  text-white rounded-sm "
            href="{{ route('orders.new.branch', ['branch' => 'ayat']) }}">
            <li id="shop-a" class="shop-item border p-4 rounded transition">

                <h3 class="text-lg font-semibold">{{ $shop1['name'] }}</h3>

            </li>
        </a>

        <a role="tab" class="p-4  text-white rounded-sm "
            href="{{ route('orders.new.branch', ['branch' => 'mexico']) }}">
            <li id="shop-b" class="shop-item border p-4 rounded  transition">
                <h3 class="text-lg font-semibold">{{ $shop2['name'] }}</h3>
            </li>
        </a>

    </ul>

    <!-- Map -->
    <div id="map" style="height: 400px;" class="mt-4"></div>
    <div class="distance-display mt-2" id="distance"></div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- JS Script -->
    <script>
        const shop1 = @json($shop1);
        const shop2 = @json($shop2);

        let map = L.map('map').setView([9.03, 38.74], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([shop1.lat, shop1.lon]).addTo(map).bindPopup(shop1.name).openPopup();
        L.marker([shop2.lat, shop2.lon]).addTo(map).bindPopup(shop2.name);

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
                })
                .addTo(map)
                .bindPopup(result?.display_name || 'Selected Location')
                .openPopup();

            map.setView([lat, lon], 15);
            calculateDistance(lat, lon);
            suggestNearestShop(lat, lon);

            endMarker.on('dragend', function(e) {
                const newLatLng = e.target.getLatLng();
                calculateDistance(newLatLng.lat, newLatLng.lng);
                suggestNearestShop(newLatLng.lat, newLatLng.lng);
            });
        }

        function calculateDistance(lat, lon) {
            const userPos = L.latLng(lat, lon);
            const shop1Pos = L.latLng(shop1.lat, shop1.lon);
            const distanceKm = userPos.distanceTo(shop1Pos) / 1000;

            distanceDiv.textContent = `Distance to Shop A: ${distanceKm.toFixed(2)} km`;
        }

        function suggestNearestShop(userLat, userLon) {
            const userPos = L.latLng(userLat, userLon);
            const shop1Pos = L.latLng(shop1.lat, shop1.lon);
            const shop2Pos = L.latLng(shop2.lat, shop2.lon);

            const dist1 = userPos.distanceTo(shop1Pos);
            const dist2 = userPos.distanceTo(shop2Pos);

            let nearestId = dist1 < dist2 ? 'shop-a' : 'shop-b';

            document.querySelectorAll('.shop-item').forEach(item => {
                item.classList.remove('border-green-500', 'border-2');
            });

            document.getElementById(nearestId).classList.add('bg-green-500', 'border-2');
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

        map.on('dblclick', function(e) {
            const {
                lat,
                lng
            } = e.latlng;
            placeEndMarker(lat, lng);
        });
    </script>
@endsection
