window.onload = function() {
    getLocation();
    modal = document.getElementById('modal-filter');
    modalMap = document.getElementById('modal-map');
    filterAdmin = document.getElementById('filterAdmin');
    if (filterAdmin) {
        searchRestaurantsAdmin();
    } else {
        searchRestaurants();
    }
}

function openModal() {
    modal.style.display = "block";
}

// REVIEW
var restLat;
var restLong;
var restMarker;

function openMapModal(address) {
    console.log('marker:', restMarker);
    if (restMarker) { // Si eixsteix...
        map.removeControl(restMarker); // Treiem el marker generat anteriorment (d'un altre restaurant)
        console.log('quitamos marker');
    }
    if (lastControl) { // Si eixsteix...
        console.log('eliminem last contorl');
        map.removeControl(lastControl); // Treiem la ruta generada anteriorment
    }
    console.log('se abre modal mapa');
    modalMap.style.display = "block";
    var greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    geocoder.geocode()
        .address(address)
        .city(`L'Hospitalet de Llobregat`)
        .region('ES')
        .run(function(error, response) {
            if (error) {
                return;
            }
            map.fitBounds(response.results[0].bounds);
            console.log('response', response);
            console.log('bounds', response.results[0].bounds);
            map.setZoom(18);
            restMarker = L.marker(response.results[0].latlng, { icon: greenIcon });
            console.log('latlng', response.results[0].latlng);
            restMarker.addTo(map)
                .bindPopup(`<b>${address}</b>`)
                .openPopup();
            restLat = response.results[0].latlng.lat;
            restLong = response.results[0].latlng.lng;
        });
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(onPositionObtained, showError);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

var myLat1, myLong1;

function onPositionObtained(position) { // Funció que obté la posició actual (segons el navegador)
    myLat1 = position.coords.latitude; // Latitud
    myLong1 = position.coords.longitude; // Longitud
    L.marker([myLat1, myLong1]).addTo(map).bindPopup("<b>La meva adreça!</b>").openPopup(); // Adreça segons navegador
    console.log('mi lat:', myLat1);
    console.log('mi long:', myLong1);
}

var lastControl;

function calcRoute(myLat1, myLong1, restLat, restLong) {
    // if (lastControl) { // Si eixsteix...
    // 	map.removeControl(lastControl); // Treiem la ruta generada anteriorment
    // }
    lastControl = L.Routing.control({
        waypoints: [
            L.latLng(myLat1, myLong1), // posició inicial
            L.latLng(restLat, restLong) // posició final
        ],
        showAlternatives: true, // Veure alternatives de ruta
        lineOptions: { // color ruta
            styles: [{ color: 'red', opacity: 1, weight: 4 }]
        },
        altLineOptions: { // color ruta alternativa
            styles: [{ color: 'black', opacity: 1, weight: 4 }]
        }
    });
    lastControl.addTo(map);
}

function calcRouteToRestaurant() {
    calcRoute(myLat1, myLong1, restLat, restLong)
}

function showError(error) {
    switch (error.code) {
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

// END REVIEW
function closeModal() {
    modal.style.display = "none";
}
// REVIEW
function closeMapModal() {
    modalMap.style.display = "none";
}
// END REVIEW

function objetoAjax() {
    var xmlhttp = false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function filter(callback, nombreRestaurante, precioMedio, valoracion, tipoCocina) {
    let userId = document.getElementById('userId');
    var token = document.getElementById('token').getAttribute('content');
    var arrayTiposCocinasSeleccionados = [];
    for (let i = 0; i < tipoCocina.length; i++) {
        if (tipoCocina[i].checked) {
            arrayTiposCocinasSeleccionados.push(`'${tipoCocina[i].value}'`);
            console.log(`Array tipos cocina: ${arrayTiposCocinasSeleccionados}`);
        }
    }
    console.log(`Array: ${arrayTiposCocinasSeleccionados}`);
    var ajax = new objetoAjax();
    var datasend = new FormData();
    datasend.append('nombreRestaurante', nombreRestaurante);
    datasend.append('precioMedio', precioMedio);
    datasend.append('valoracion', valoracion);
    datasend.append('tipoCocina', arrayTiposCocinasSeleccionados);
    if (userId) {
        datasend.append('userId', userId.value);
    }
    datasend.append('_token', token);
    ajax.open('POST', 'filter', true);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            if (ajax.status != 200) {
                console.log('App::Problems on search request: ' + ajax.status);
            } else {
                console.log('App::Obtained search response');
                var respuesta = JSON.parse(ajax.responseText);
                callback(respuesta);
            }
        }
    }
    ajax.send(datasend);
}

function renderRestaurants(respuesta) {
    var section = document.getElementById('section-3');
    let filterEstandard = document.getElementById('filterEstandard');
    var renderedResults = '';
    if (respuesta.length == 0) {
        renderedResults += 'No se han encontrado resultados';
    } else {
        console.log('App::Obtained search data: ', respuesta);
        for (let i = 0; i < respuesta.length; i++) {
            renderedResults += '<div>';
            renderedResults += '<div class="container-img">';
            renderedResults += '<img src="data:image/png;base64,' + respuesta[i].Ruta_Imatge + '" alt="error" width="100px" height="auto"></img>';
            // REVIEW
            if (filterEstandard) { // Si no troba id_favorit, llavors no posem classe. Si existeix, posem la clase 'active'
                renderedResults += `<i class="fas fa-star ${respuesta[i].Id_favorit != null ? 'active' : ''}" onclick="favorito(event, ${respuesta[i].Id_restaurant})"></i>`;
            }
            // END REVIEW
            renderedResults += '</div>';
            renderedResults += '<div class="container-details">';
            renderedResults += `<h4>${respuesta[i].Nom_restaurant}</h4>`;
            renderedResults += '<div class="container--progress">';
            renderedResults += '<div class="capa-progress"></div>';
            renderedResults += `<div id="progress" class="progress" style="width: calc(${respuesta[i].Valoracio} * 100%/5)"></div>`;
            renderedResults += '</div>';
            renderedResults += '<h4>' + respuesta[i].Preu_mitja_restaurant + '€</h4>';
            // REVIEW // Al fer click en l'adreça, s'obre un modal. (passem com a paràmetre la direcció del restaurant) 
            renderedResults += `<div class="container--info"><h4 onclick="openMapModal('${respuesta[i].Adreca_restaurant}')" class="adress">${respuesta[i].Adreca_restaurant}</h4><div><a href="verRestaurante/${respuesta[i].Id_restaurant}"><i class="fas fa-info-circle"></i></a></div></div>`;
            // END REVIEW
            renderedResults += '</div>';
            renderedResults += '</div>';
        }
    }
    section.innerHTML = renderedResults;
}

function renderRestaurantsAdmin(respuesta) {
    var section = document.getElementById('section-3');
    var renderedResults = '';
    if (respuesta.length == 0) {
        renderedResults += 'No se han encontrado resultados';
    } else {
        console.log('App::Obtained search data: ', respuesta);
        for (let i = 0; i < respuesta.length; i++) {
            renderedResults += '<div>';
            renderedResults += '<div class="container-img">';
            renderedResults += '<img src="data:image/png;base64,' + respuesta[i].Ruta_Imatge + '" alt="error" width="100px" height="auto"></img>';
            renderedResults += '</div>';
            renderedResults += '<div class="container-details">';
            renderedResults += '<h4>' + respuesta[i].Nom_restaurant + '</h4>';
            renderedResults += '<div class="container--progress">';
            renderedResults += '<div class="capa-progress"></div>';
            renderedResults += `<div id="progress" class="progress" style="width: calc(${respuesta[i].Valoracio} * 100%/5)"></div>`;
            renderedResults += '</div>';
            renderedResults += '<h4>' + respuesta[i].Preu_mitja_restaurant + '€</h4>';
            renderedResults += '<h4>' + respuesta[i].Adreca_restaurant + '</h4>';
            renderedResults += '<input type="hidden" name="id_restaurant" id="id_restaurant" href="modificarView">';
            renderedResults += '<div class="btn--modificar-eliminar">';
            renderedResults += `<button onclick="eliminarRestaurante(${respuesta[i].Id_restaurant})">Eliminar</button>`;
            renderedResults += `<a href="modificarRestauranteDatos/${respuesta[i].Id_restaurant}">Modificar</a>`;
            renderedResults += '</div>';
            renderedResults += '</div>';
            renderedResults += '</div>';
        }
    }
    section.innerHTML = renderedResults;
}


function searchRestaurants() {
    var nombreRestaurante = document.getElementById('search--rest').value;
    var precioMedio = document.getElementById('precio_medio').value;
    var valoracion = document.getElementById('valoracion').value;
    var tipoCocina = document.querySelectorAll('.filtro--tipo_cocina');

    filter(renderRestaurants, nombreRestaurante, precioMedio, valoracion, tipoCocina);
}

function searchRestaurantsAdmin() {
    var nombreRestaurante = document.getElementById('search--rest').value;
    var precioMedio = document.getElementById('precio_medio').value;
    var valoracion = document.getElementById('valoracion').value;
    var tipoCocina = document.querySelectorAll('.filtro--tipo_cocina');

    filter(renderRestaurantsAdmin, nombreRestaurante, precioMedio, valoracion, tipoCocina);
}

function eliminarRestaurante($id, event) {
    opcion = confirm("¿Está seguro de borrar el restaurante?");
    if (opcion == false) {
        event.preventDefault();
    } else {
        var token = document.getElementById('token').getAttribute('content');
        var ajax = new objetoAjax();
        var datasend = new FormData();
        datasend.append('_token', token);
        datasend.append('id_restaurante', $id);
        ajax.open('POST', 'eliminarRestaurante', true);
        ajax.send(datasend);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                searchRestaurantsAdmin();
            }
        }
    }
}

/**
 * Agrega o elimina restaurants de favorit.
 * @param {*} event 
 * @param {*} idRestaurant 
 */
function favorito(event, idRestaurant) {
    event.target.classList.toggle('active'); // Si no té la classe se la posem, si no, li treiem.
    let userId = document.getElementById('userId').value;
    var token = document.getElementById('token').getAttribute('content');
    var ajax = new objetoAjax();
    var datasend = new FormData();
    datasend.append('_token', token);
    datasend.append('id_restaurante', idRestaurant);
    datasend.append('id_usuari', userId);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4 && ajax.status != 200) {
            // searchRestaurants();
            // Si va malament la resposta, mostrem estrella en l'estat anterior a la que estava (abans de que li fes click l'usuari)
            event.target.classList.toggle('active'); // Si no té la classe se la posem, si no, li treiem.
        }
    }
    ajax.open('POST', 'favorito', true);
    ajax.send(datasend);
}