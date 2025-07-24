<!DOCTYPE html>
<html>
<head>
    <title>Map Search</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 400px; margin-top: 1rem; }
        .search-results {
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            z-index: 1000;
            width: 300px;
            max-height: 150px;
            overflow-y: auto;
            font-weight: bold;
            color: black;
        }
        .search-results div {
            padding: 5px;
            cursor: pointer;
        }
        .search-results div:hover {
            background: #eee;
        }
    </style>
</head>
<body>
    <div style="position: relative;">
        <input type="text" id="location-input" placeholder="Search location..." style="width:300px; padding:6px;" />
        <div class="search-results" id="results"></div>
    </div>

    <div id="map"></div>

    <p>Latitude: <span id="lat">-</span></p>
    <p>Longitude: <span id="lon">-</span></p>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([9.03, 38.74], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker = L.marker([9.03, 38.74]).addTo(map);

        const input = document.getElementById('location-input');
        const resultsDiv = document.getElementById('results');
        let timeout;

        input.addEventListener('input', () => {
            const query = input.value.trim();

            resultsDiv.innerHTML = '';

            if (query.length < 3) return;

            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetch(`/map/search?query=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        data.forEach(place => {
                            const div = document.createElement('div');
                            div.textContent = place.display_name;
                            div.onclick = () => {
                                const lat = parseFloat(place.lat);
                                const lon = parseFloat(place.lon);
                                map.setView([lat, lon], 15);
                                marker.setLatLng([lat, lon]);

                                document.getElementById('lat').innerText = lat.toFixed(5);
                                document.getElementById('lon').innerText = lon.toFixed(5);
                                resultsDiv.innerHTML = '';
                            };
                            resultsDiv.appendChild(div);
                        });
                    });
            }, 500); // debounce
        });
    </script>
</body>
</html>
