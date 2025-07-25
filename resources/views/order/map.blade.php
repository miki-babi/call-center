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
        }



        .search-box {
            margin: 10px;
            padding-bottom: 10px;
        }

        .search-results {
            margin-top: 25px;
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
    </style>
    </head>

    <body>


        <div class="search-box w-full justify-end flex pr-4">
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
                        .bindPopup('Warehouse')
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
