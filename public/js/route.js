var restLat;
var restLong;
var restMarker;
function getLocation() {
	control = false;
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(onPositionObtained, showError);
	} else { 
		alert("Geolocation is not supported by this browser.");
	}
}

var myLat1, myLong1;
function onPositionObtained(position) { // Funció que obté la posició actual (segons el navegador)
	myLat1 = position.coords.latitude; // La meva Latitud
	myLong1 = position.coords.longitude; // LA meva Longitud
	L.marker([myLat1,myLong1]).addTo(map).bindPopup("<b>La meva adreça!</b>").openPopup(); // Adreça segons navegador
    console.log('mi lat:', myLat1);
    console.log('mi long:', myLong1);
    calcRoute(myLat1, myLong1, restLat, restLong);
}

var lastControl;
function calcRoute(myLat1, myLong1, restLat, restLong) {
    
    lastControl = L.Routing.control({
		waypoints: [
			L.latLng(myLat1, myLong1), // posició inicial
			L.latLng(restLat, restLong) // posició final
		],
		language: 'es', // Idioma indicador de ruta
		showAlternatives: true, // Veure alternatives de ruta
		lineOptions: { // color ruta
			styles: [{color: 'red', opacity: 1, weight: 4}]
		},
		altLineOptions: { // color ruta alternativa
			styles: [{color: 'black', opacity: 1, weight: 4}]
		}
    });
    lastControl.addTo(map);
}

var control = true;
function calcRouteToRestaurant() {
	if (control) {
		getLocation();
	}
}

function showError(error) {
	switch(error.code) {
		case error.PERMISSION_DENIED:
		x.innerHTML = "User denied the request for Geolocation."
		break;
		case error.POSITION_UNAVAILABLE:
		x.innerHTML = "Location information is unavailable."
		break;
		case error.TIMEOUT:
		x.innerHTML = "The request to get user location timed out."
		break;
		case error.UNKNOWN_ERROR:
		x.innerHTML = "An unknown error occurred."
		break;
	}
}