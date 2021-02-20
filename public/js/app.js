window.onload = function() {
    modal = document.getElementById('modal-filter');
    filterAdmin = document.getElementById('filterAdmin');
    if (filterAdmin){
        searchRestaurantsAdmin();
    }else{
        searchRestaurants();  
    }
}

function openModal() {
    modal.style.display = "block";
}

function closeModal() {
    modal.style.display = "none";
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

function filter(callback, nombreRestaurante, precioMedio, valoracion, tipoCocina) {
    let userId = document.getElementById('userId').value;
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
    datasend.append('userId', userId);
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
            if (filterEstandard) {
                renderedResults += `<i class="fas fa-star ${respuesta[i].Id_favorit != null ? 'active' : ''}" onclick="favorito(event, ${respuesta[i].Id_restaurant})"></i>`;
            }
            // END REVIEW
            renderedResults += '</div>';
            renderedResults += '<div class="container-details">';
            renderedResults += '<h4>' + respuesta[i].Nom_restaurant + '</h4>';
            renderedResults += '<div class="container--progress">';
            renderedResults += '<div class="capa-progress"></div>';
            renderedResults += `<div id="progress" class="progress" style="width: calc(${respuesta[i].Valoracio} * 100%/5)"></div>`;
            renderedResults += '</div>';
            renderedResults += '<h4>' + respuesta[i].Preu_mitja_restaurant + '€</h4>';
            renderedResults += `<div class="container--info"><h4>${respuesta[i].Adreca_restaurant}</h4><div><a href="verRestaurante/${respuesta[i].Id_restaurant}"><i class="fas fa-info-circle"></i></a></div></div>`;
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
            // renderedResults += '<div><img src="img/forkoverlay.png" alt="'+respuesta[i].Valoracio+'"></div>';
            renderedResults += `<div id="progress" class="progress" style="width: calc(${respuesta[i].Valoracio} * 100%/5)"></div>`;
            renderedResults += '</div>';
            renderedResults += '<h4>' + respuesta[i].Preu_mitja_restaurant + '€</h4>';
            renderedResults += '<h4>' + respuesta[i].Adreca_restaurant + '</h4>';
            // renderedResults += `<button href="modificarRestaurante/${respuesta[i].Id_restaurant}">Modificar</button>`;
            renderedResults += '<input type="hidden" name="id_restaurant" id="id_restaurant" href="modificarView">';
            renderedResults += '<div class="btn--modificar-eliminar">';
            // renderedResults += '<a href="modificarView">Modificar</a>';
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
