window.onload = function() {
    modal = document.getElementById('modal-filter');
    span1 = document.getElementById('span1');
    span2 = document.getElementById('span2');
    modalMap = document.getElementById('modal-map');
    mapafilter = document.getElementById("mapfilter");
    filterAdmin = document.getElementById('filterAdmin');
    map1 = L.map('mapfilter');
    if (filterAdmin) {
        searchRestaurantsAdmin();
    } else {
        searchRestaurants();
    }
    //Modal tag
    modalTag = document.getElementById('modal-tag');
    renderTags();
}

function openModal() {
    modal.style.display = "block";
}

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
        console.log('eliminem last control');
        map.removeControl(lastControl); // Treiem la ruta generada anteriorment
    }
    if (miUbicacion) { // Si eixsteix...
        console.log('eliminem la meva ubicació');
        map.removeControl(miUbicacion); // Treiem la ruta generada anteriorment
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
    control = false;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(onPositionObtained, showError);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

var myLat1, myLong1;
var miUbicacion;

function onPositionObtained(position) { // Funció que obté la posició actual (segons el navegador)
    myLat1 = position.coords.latitude; // Latitud
    myLong1 = position.coords.longitude; // Longitud
    miUbicacion = L.marker([myLat1, myLong1]).addTo(map).bindPopup("<b>La meva adreça!</b>").openPopup(); // Adreça segons navegador
    console.log('mi lat:', myLat1);
    console.log('mi long:', myLong1);
    calcRoute(myLat1, myLong1, restLat, restLong);
}

var lastControl;

function calcRoute(myLat1, myLong1, restLat, restLong) {

    var greenIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    var orangeIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    lastControl = L.Routing.control({
        waypoints: [
            L.latLng(myLat1, myLong1), // posició inicial
            L.latLng(restLat, restLong) // posició final
        ],

        createMarker: function(i, wp, nWps) {
            if (i === 0 || i === nWps - 1) {
                // here change the starting and ending icons
                return L.marker(wp.latLng, {
                    icon: orangeIcon // here pass the custom marker icon instance
                });
            } else {
                // here change all the others
                return L.marker(wp.latLng, {
                    icon: greenIcon
                });
            }
        },

        language: 'es',
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

var control = true;

function calcRouteToRestaurant() {
    if (control) {
        getLocation();
    }
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


function closeModal() {
    modal.style.display = "none";
}

function closeMapModal() {
    modalMap.style.display = "none";
}

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

function filter(callback, nombreRestaurante, precioMedio, valoracion, tipoCocina, favorito, tipoCategoria) {
    let userId = document.getElementById('userId');
    var token = document.getElementById('token').getAttribute('content');
    var arrayTiposCocinasSeleccionados = [];
    var arrayTiposCatSeleccionados = [];
    for (let i = 0; i < tipoCocina.length; i++) {
        if (tipoCocina[i].checked) {
            arrayTiposCocinasSeleccionados.push(`'${tipoCocina[i].value}'`);
            console.log(`Array tipos cocina: ${arrayTiposCocinasSeleccionados}`);
        }
    }
    for (let i = 0; i < tipoCategoria.length; i++) {
        if (tipoCategoria[i].checked) {
            arrayTiposCatSeleccionados.push(`'${tipoCategoria[i].value}'`);
            console.log(`Array tipos cocina: ${arrayTiposCatSeleccionados}`);
        }
    }

    var ajax = new objetoAjax();
    var datasend = new FormData();
    datasend.append('nombreRestaurante', nombreRestaurante);
    datasend.append('precioMedio', precioMedio);
    datasend.append('valoracion', valoracion);
    datasend.append('tipoCocina', arrayTiposCocinasSeleccionados);
    datasend.append('tipoCat', arrayTiposCatSeleccionados);
    if (favorito) {
        if (favorito.checked) {
            datasend.append('favorito', favorito);
        }
    }
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
            renderedResults += '<img src="storage/' + respuesta[i].Ruta_Text_Imatge + '" alt="error" width="100px" height="auto"></img>';
            // REVIEW
            if (filterEstandard) { // Si no troba id_favorit, llavors no posem classe. Si existeix, posem la clase 'active'
                renderedResults += `<i class="fas fa-star ${respuesta[i].Id_favorit != null ? 'active' : ''}" onclick="favorito(event, ${respuesta[i].Id_restaurant})"></i>`;
            }
            renderedResults += '</div>';
            renderedResults += '<div class="container-details">';
            renderedResults += `<h4 class="idrest" id="idrest" style="display:none;">${respuesta[i].Id_restaurant}</h4>`;
            renderedResults += `<h4 class="nomrest" id="nomrest">${respuesta[i].Nom_restaurant}</h4>`;
            renderedResults += '<div class="container--progress">';
            renderedResults += '<div class="capa-progress"></div>';
            renderedResults += `<div id="progress" class="progress" style="width: calc(${respuesta[i].Valoracio} * 100%/5)"></div>`;
            renderedResults += '</div>';
            renderedResults += '<h4>' + respuesta[i].Preu_mitja_restaurant + '€</h4>';
             // Al fer click en l'adreça, s'obre un modal. (passem com a paràmetre la direcció del restaurant) 
            renderedResults += `<div class="container--info"><div class="icon-map"><p><i class="fas fa-map-marked-alt"></i></p><h4 id="adress" class="adress" onclick="openMapModal('${respuesta[i].Adreca_restaurant}')" class="adress">${respuesta[i].Adreca_restaurant}</h4></div><div><a href="verRestaurante/${respuesta[i].Id_restaurant}"><i class="fas fa-info-circle"></i></a></div></div>`;

            renderedResults += '<input type="hidden" class="Ciutat_restaurant" value="' + respuesta[i].Ciutat_restaurant + '"></input>';
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
            renderedResults += '<img src="storage/' + respuesta[i].Ruta_Text_Imatge + '" alt="error" width="100px" height="auto"></img>';
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
    var tipoCategoria = document.querySelectorAll('.filtro--tipo_categoria');
    var favorito = document.getElementById('filtrofav');
    let btnVerMapa = document.getElementById('mapfilter');

    if (btnVerMapa) { // Controlem que existeixi la classe "displat-none" per a tancar o no el modal del filtre
        if (btnVerMapa.className != "display-none") {
            console.log('que no te cierres!!');
        } else {
            console.log('ciérrate ya');
            closeModal();
        }
    }

    filter(renderRestaurants, nombreRestaurante, precioMedio, valoracion, tipoCocina, favorito, tipoCategoria);
}

function searchRestaurantsAdmin() {
    var nombreRestaurante = document.getElementById('search--rest').value;
    var precioMedio = document.getElementById('precio_medio').value;
    var valoracion = document.getElementById('valoracion').value;
    var tipoCocina = document.querySelectorAll('.filtro--tipo_cocina');
    var tipoCategoria = document.querySelectorAll('.filtro--tipo_categoria');
    var favorito = "";

    filter(renderRestaurantsAdmin, nombreRestaurante, precioMedio, valoracion, tipoCocina, favorito, tipoCategoria);
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

//  Tags
function openModalTags() {
    modalTag.style.display = "block";
}

function closeModalTag() {
    modalTag.style.display = "none";
}

function renderTags() {
    //Recogemos variables de la pagina
    var section = document.getElementById('tags');
    var token = document.getElementById('token').getAttribute('content');
    var idUsuario = document.getElementById('idUsuario').value;
    var renderedResults = '';
    var cont = 0;

    //Ajax
    var ajax = new objetoAjax();
    ajax.open('POST', 'getTags', true);
    var datasend = new FormData();
    datasend.append('_token', token);
    datasend.append('idUsuario', idUsuario);
    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            var respuesta = JSON.parse(ajax.responseText);

            for (let i = 0; i < respuesta.length; i++) {
                if (cont % 3 == 0) {
                    renderedResults += '<div class="rowTag">';
                }
                cont++;
                renderedResults += '<span class="tag"><span class="bold">' + respuesta[i].Nom_restaurant + ':</span> ' + respuesta[i].Nom_tag + '</span>';
                if (cont % 3 == 0) {
                    renderedResults += '</div>';
                }
            }
            section.innerHTML = renderedResults;
        } else {
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }
    }
    ajax.send(datasend);
}

//  Filtro Mapa

function closeSpan() {
    span2.classList.remove("display-none");
    span2.classList.add("display-block");
    span1.classList.remove("display-block");
    span1.classList.add("display-none");
    // REVIEW
    if (restMarker) { // Si eixsteix...
        map1.removeControl(restMarker); // Treiem el marker generat anteriorment (d'un altre restaurant)
        console.log('quitamos marker');
    }
}

function openModalFilterMap() {
    modal.style.display = "block";
    closeSpan();
    var nomrest = document.querySelectorAll('.nomrest');
    var arraynomrest = [];
    for (let i = 0; i < nomrest.length; i++) {
        arraynomrest.push(nomrest[i].innerHTML);
        // console.log(arraynomrest);
    }
    var Ciutat_restaurant = document.querySelectorAll('.Ciutat_restaurant');
    var arrayCiudad = [];
    for (let i = 0; i < Ciutat_restaurant.length; i++) {
        arrayCiudad.push(Ciutat_restaurant[i].value);
    }
    var adress = document.querySelectorAll('.adress');
    var arrayadress = [];
    for (let i = 0; i < adress.length; i++) {
        arrayadress.push(adress[i].innerHTML);
        //console.log(arrayadress);
    }
    var idrest = document.querySelectorAll('.idrest');
    var arrayid = [];
    for (let i = 0; i < idrest.length; i++) {
        arrayid.push(idrest[i].innerHTML);
        //console.log(arrayadress);
    }
    openMapFilter(arrayadress, arraynomrest, arrayid, arrayCiudad);

}

function closeModalFilterMap() {
    modal.style.display = "none";
    mapafilter.classList.remove("display-block");
    mapafilter.classList.add("display-none");
    closeSpan();

}

function openMapFilter(arrayadress, arraynomrest, arrayid, arrayCiudad) {
    mapafilter.classList.remove("display-none");
    mapafilter.classList.add("display-block");
    //console.log('map:', mapafilter);
    //adrecaRestaurant.addEventListener('blur', markerMap);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map1);

    var greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    for (let i = 0; i < arrayadress.length; i++) {
        geocoder.geocode()
            .address(arrayadress[i])
            .city(arrayCiudad[i])
            .run(function(error, response) {
                if (error) {
                    console.log('Error', error);
                    return;
                } else {
                    // console.log('Bounds: ', response.results[0].bounds);
                    map1.fitBounds(response.results[0].bounds);
                    map1.setZoom(9);
                    restMarker = L.marker(response.results[0].latlng, { icon: greenIcon });
                    restMarker.addTo(map1)
                        .bindPopup(arraynomrest[i]);
                    restMarker.on('mouseover', function(e) {
                        this.openPopup();
                    });
                    restMarker.on('mouseout', function(e) {
                        this.closePopup();
                    });
                    restMarker.on('click', function(e) {
                        location.href = "verRestaurante/" + arrayid[i];
                        //this.openPopup();
                        //disable mouesout behavior?
                    });
                }
            });
    }

}