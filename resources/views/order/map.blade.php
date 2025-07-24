@extends('layout.app')

@section('content')
<style>
    #map { height: 400px; margin-bottom: 10px; }
    #location-search { width: 100%; padding: 8px; margin-bottom: 5px; }
    
    #distance-display { font-weight: bold; margin-bottom: 10px; }
    .search-results {
    background: #fff;
    border: 1px solid #ccc;
    max-height: 150px;
    overflow-y: auto;
    color: #000; /* black text */
    font-weight: bold; /* bold text */
}

.search-results div {
    padding: 5px;
    cursor: pointer;
    color: #000; /* black text */
    font-weight: bold; /* bold text */
}

.search-results div:hover {
    background: #eee;
}
</style>

<div class="container mx-auto px-4">
    <input type="text" id="location-search" placeholder="Type your location in Addis Ababa...">
    <div class="search-results" id="search-results"></div>
    <div id="distance-display">Distance: -- km</div>
    <div id="map"></div>
</div>

<!-- Leaflet scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const storeLatLng = [9.03, 38.74]; // Addis default
    const map = L.map('map').setView(storeLatLng, 13);
    const searchInput = document.getElementById('location-search');
    const resultsContainer = document.getElementById('search-results');
    const distanceDisplay = document.getElementById('distance-display');
    let routingControl = null;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker(storeLatLng).addTo(map).bindPopup("Store Location").openPopup();

    function renderResults(data) {
        resultsContainer.innerHTML = '';
        data.forEach(place => {
            const div = document.createElement('div');
            div.textContent = place.display_name;
            div.addEventListener('click', () => {
                resultsContainer.innerHTML = '';
                const latLng = [parseFloat(place.lat), parseFloat(place.lon)];
                showRouteTo(latLng);
            });
            resultsContainer.appendChild(div);
        });
    }

    function showRouteTo(destinationLatLng) {
        if (routingControl) map.removeControl(routingControl);

        routingControl = L.Routing.control({
            waypoints: [L.latLng(storeLatLng), L.latLng(destinationLatLng)],
            routeWhileDragging: false,
            show: false,
            addWaypoints: false,
            createMarker: () => null
        }).addTo(map);

        const distance = map.distance(storeLatLng, destinationLatLng);
        const km = (distance / 1000).toFixed(2);
        distanceDisplay.textContent = `Distance: ${km} km`;

        document.cookie = `delivery_distance=${km}; path=/; max-age=3600;`;
    }

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();
        if (query.length < 3) {
            resultsContainer.innerHTML = '';
            return;
        }

        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ' Addis Ababa')}&addressdetails=1&limit=5&viewbox=38.65,9.10,38.85,8.90&bounded=1`;

        fetch(url)
            .then(res => res.json())
            .then(data => renderResults(data))
            .catch(err => console.error('Search error:', err));
    });
});
</script>
@endsection
