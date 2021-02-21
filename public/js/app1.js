window.onload = function() {
    renderComentarios();
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

//  COMENTARIOS
function renderComentarios() {
    var section = document.getElementById('content--comentarios');
    var id_restaurant = document.getElementById('id_restaurant').value;
    var token = document.getElementById('token').getAttribute('content');
    var renderedResults = '';
    var ajax = new objetoAjax();
    ajax.open('POST', '../getComentarios', true);
    var datasend = new FormData();
    datasend.append('id_restaurant', id_restaurant);
    datasend.append('_token', token);
    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            var respuesta = JSON.parse(ajax.responseText);
            for (let i = 0; i < respuesta.length; i++) {
                renderedResults += '<div class="comentario">';
                renderedResults += `<h3>${respuesta[i].Nom_usuari}</h3>`;
                renderedResults += `<p>${respuesta[i].Comentari}</p>`;
                renderedResults += '</div>';
            }
            section.innerHTML = renderedResults;
        } else {
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }
    }
    ajax.send(datasend);
}

function enviarComentario() {
    let comentario = document.getElementById('nuevo_comentario').value;
    let id_restaurant = document.getElementById('id_restaurant').value;
    let id_usuari = document.getElementById('id_usuari').value;
    var token = document.getElementById('token').getAttribute('content');
    var ajax = new objetoAjax();
    ajax.open('POST', '../addComentario', true);
    var datasend = new FormData();
    datasend.append('comentario', comentario);
    datasend.append('id_restaurant', id_restaurant);
    datasend.append('id_usuari', id_usuari);
    datasend.append('_token', token);
    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            document.getElementById("nuevo_comentario").value = "";
            console.log('comentario añadido');
            renderComentarios();
        } else {
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }
    }
    ajax.send(datasend);
}
//  FIN COMENTARIOS

// VALORACIÓN
function getValoracion() {
    var forquilla = document.getElementsByClassName('fork-inner');
    var token = document.getElementById('token').getAttribute('content');
    let id_restaurant = document.getElementById('id_restaurant').value;
    let id_usuari = document.getElementById('id_usuari').value;
    var ajax = new objetoAjax();
    ajax.open('POST', '../getValoracion', true);
    var datasend = new FormData();
    datasend.append('id_restaurant', id_restaurant);
    datasend.append('id_usuari', id_usuari);
    datasend.append('_token', token);
    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            var respuesta = JSON.parse(ajax.responseText);
            let valoracion = parseFloat(respuesta);
            for (let i = 0; i < forquilla.length; i++) {
                if (i == valoracion) {
                    return;
                }
                forquilla[i].style.backgroundColor = "#00ccbc";
            }
        } else {
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }
    }
    ajax.send(datasend);
}

function puntuar(fork) {
    let forquilla = document.getElementsByClassName('fork-inner');
    for (let i = 0; i < forquilla.length; i++) {
        forquilla[i].style.backgroundColor = "transparent";
    }
    let puntuacion = fork;
    var token = document.getElementById('token').getAttribute('content');
    let id_restaurant = document.getElementById('id_restaurant').value;
    let id_usuari = document.getElementById('id_usuari').value;
    var ajax = new objetoAjax();
    ajax.open('POST', '../puntuar', true);
    var datasend = new FormData();
    datasend.append('puntuacion', puntuacion);
    datasend.append('id_restaurant', id_restaurant);
    datasend.append('id_usuari', id_usuari);
    datasend.append('_token', token);
    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            getValoracion();
        } else {
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }

    }
    ajax.send(datasend);
}

// FIN VALORACIÓN