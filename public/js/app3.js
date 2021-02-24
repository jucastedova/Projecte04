window.onload = function() {
    markerMap();
}

let adrecaRestaurant = document.getElementById('Adreca_restaurant');
let ciutatRestaurant = document.getElementById('Ciutat_restaurant');
let containerMap = document.getElementById('container-map');
console.log('map:', containerMap);
adrecaRestaurant.addEventListener('blur', markerMap);
ciutatRestaurant.addEventListener('blur', markerMap);

var geocoder = L.esri.Geocoding.geocodeService();
var map = L.map('map');
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


var restMarker;

function markerMap() {
    if (restMarker) { // Si eixsteix...
        console.log('eliminem last control');
        map.removeControl(restMarker); // Treiem el marker
    }
    containerMap.classList.remove('display-none');
    // containerMap.classList.add('map--create-modify');
    // console.log(adrecaRestaurant.value);


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
        .run(function(error, response) {
            if (error) {
                console.log('Error', error);
                return;
            } else {
                let addressResponse = response.results[0].text;
                let splitAddress = addressResponse.split(",");
                let errorAddress = document.getElementById('error-address');
                errorAddress.textContent = "";
                // console.log('array splitAdress', splitAddress);
                // console.log('splitAdress length', splitAddress.length);
                console.log('Response: ', response);
                if (splitAddress.length > 3) { // Llavors la direcció existeix
                    // console.log('Bounds: ',response.results[0].bounds);
                    // console.log('direcció existeix')
                    map.fitBounds(response.results[0].bounds);
                    map.setZoom(18);
                    restMarker = L.marker(response.results[0].latlng, { icon: greenIcon });
                    restMarker.addTo(map)
                        .bindPopup(`<b>${adrecaRestaurant.value}</b>`)
                        .openPopup();
                } else {
                    console.log('direcció NO existeix');
                    errorAddress.textContent = "Dirección incorrecta";
                }
            }
        });
}
