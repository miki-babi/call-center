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
    let timeout = null;

    const input = document.getElementById('search');
    const resultsDiv = document.getElementById('results');

    input.addEventListener('input', function () {
        const query = this.value.trim();

        // Clear previous timeout
        clearTimeout(timeout);

        if (query.length < 2) {
            resultsDiv.innerHTML = '';
            return;
        }

        // Wait 500ms after user stops typing
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
                map.setView([lat, lon], 15);
                L.marker([lat, lon]).addTo(map)
                    .bindPopup(result.display_name)
                    .openPopup();
                resultsDiv.innerHTML = '';
            });
            resultsDiv.appendChild(div);
        });
    }
</script>

@endsection
