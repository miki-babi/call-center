<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Map Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    
    <style>
        #map {
            height: 500px;
            width: 100%;
        }

        .search-box {
            margin: 10px;
        }

        .search-results {
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

<div class="search-box">
    <input type="text" id="search" placeholder="Search location..." style="width: 300px; padding: 6px;">
    <div id="results" class="search-results"></div>
</div>

<div id="map"></div>
<div class="distance-display" id="distance"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    let map = L.map('map').setView([9.03, 38.74], 13); // Centered on Addis Ababa

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let startMarker = null;
    let endMarker = null;

    let timeout = null;

    const input = document.getElementById('search');
    const resultsDiv = document.getElementById('results');
    const distanceDiv = document.getElementById('distance');

    input.addEventListener('input', function () {
        const query = this.value.trim();

        clearTimeout(timeout);

        if (query.length < 2) {
            resultsDiv.innerHTML = '';
            return;
        }

        timeout = setTimeout(() => {
            fetch(`/leaflet/search?query=${encodeURIComponent(query)}`)
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
            div.addEventListener('click', () => {
                const lat = parseFloat(result.lat);
                const lon = parseFloat(result.lon);
                if (endMarker) map.removeLayer(endMarker);
                endMarker = L.marker([lat, lon]).addTo(map)
                    .bindPopup(result.display_name)
                    .openPopup();
                map.setView([lat, lon], 15);
                resultsDiv.innerHTML = '';
                calculateDistance(lat, lon);
            });
            resultsDiv.appendChild(div);
        });
    }

    function calculateDistance(lat, lon) {
        if (!startMarker) {
            // Use a fixed warehouse location (e.g., 9.03, 38.74)
            startMarker = L.marker([9.03, 38.74], { color: 'green' })
                .addTo(map)
                .bindPopup('Warehouse')
                .openPopup();
        }

        const from = startMarker.getLatLng();
        const to = L.latLng(lat, lon);
        const distanceKm = from.distanceTo(to) / 1000;

        distanceDiv.textContent = `Distance: ${distanceKm.toFixed(2)} km`;
    }
</script>

</body>
</html>
