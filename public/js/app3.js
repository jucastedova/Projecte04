window.onload = function() {
    markerMap();
}

let adrecaRestaurant = document.getElementById('Adreca_restaurant');
let ciutatRestaurant = document.getElementById('Ciutat_restaurant');
let CPRestaurant = document.getElementById('CP_restaurant');
let containerMap = document.getElementById('container-map');
adrecaRestaurant.addEventListener('blur', markerMap);
ciutatRestaurant.addEventListener('blur', markerMap);
CPRestaurant.addEventListener('blur', markerMap);

var geocoder = L.esri.Geocoding.geocodeService();
var map = L.map('map');
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


var restMarker;

function markerMap() {
    if (restMarker) { // Si eixsteix...
        map.removeControl(restMarker); // Treiem el marker
    }
    containerMap.classList.remove('display-none');

    var greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    geocoder.geocode()
        .address(adrecaRestaurant.value)
        .city(ciutatRestaurant.value)
        .postal(CPRestaurant.value)
        .run(function(error, response) {
            if (error) {
                return;
            } else {
                let errorAddress = document.getElementById('error-address');
                errorAddress.textContent = "";
                let score = response.results[0].score;
                if (score > 90) { // Si té un Score superior al 90%...
                    map.fitBounds(response.results[0].bounds);
                    map.setZoom(18);
                    restMarker = L.marker(response.results[0].latlng, { icon: greenIcon });
                    restMarker.addTo(map)
                        .bindPopup(`<b>${adrecaRestaurant.value}</b>`)
                        .openPopup();
                } else {
                    errorAddress.textContent = "Dirección incorrecta";
                }
            }
        });
}