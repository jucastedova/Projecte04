window.onload = function() {
    renderComentarios();
    renderRestaurantTags();
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
    var datasend = new FormData();
    datasend.append('id_restaurant', id_restaurant);
    datasend.append('_token', token);
    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            var respuesta = JSON.parse(ajax.responseText);
            for (let i = 0; i < respuesta.length; i++) {
                if (respuesta == '0') {
                    renderedResults += `<p>0 opiniones</p>`;
                } else {
                    renderedResults += '<div class="comentario">';
                    renderedResults += `<h3>${respuesta[i].Nom_usuari}</h3>`;
                    renderedResults += `<p>${respuesta[i].Comentari}</p>`;
                    renderedResults += '</div>';
                }
            }
            section.innerHTML = renderedResults;
        }
    }
    ajax.open('POST', '../getComentarios', true);
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
            renderComentarios();
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
        }
    }
    ajax.send(datasend);
}

// FIN VALORACIÓN

function renderRestaurantTags() {
    //Recogemos variables de la pagina
    section = document.getElementById('mostrarTags');
    token = document.getElementById('token').getAttribute('content');
    idUsuario = document.getElementById('id_usuari').value;
    id_restaurant = document.getElementById('id_restaurant').value;
    renderedResults = '';
    var cont = 0;
    //Ajax
    var ajax = new objetoAjax();
    ajax.open('POST', '../getRestaurantTags', true);
    var datasend = new FormData();
    datasend.append('_token', token);
    datasend.append('idUsuario', idUsuario);
    datasend.append('id_restaurant', id_restaurant);
    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            var respuesta = JSON.parse(ajax.responseText);
            if (respuesta.length == 0) {
                renderedResults = "No has asignado ningún tag a este restaurante.";
            }

            for (let i = 0; i < respuesta.length; i++) {
                if (i % 3 == 0) {
                    renderedResults += '<div class="rowTag">';
                }
                cont++;
                renderedResults += '<span>' + respuesta[i].Nom_tag + '<i class="fa fa-times" aria-hidden="true" onclick="eliminarTag(' + respuesta[i].Id_tag + ')"></i></span>';
                if (cont % 3 == 0) {
                    renderedResults += '</div>';
                }
            }
            section.innerHTML = renderedResults;
        }
    }
    ajax.send(datasend);
}

function añadirTag(e) {
    e.which = e.which || e.keyCode;
    if (e.which == 13) {
        tagValue = document.getElementById('tag').value;
        tag = document.getElementById('tag');
        id_restaurant = document.getElementById('id_restaurant').value;
        id_usuari = document.getElementById('id_usuari').value;
        msgTag = document.getElementById('msgTag');
        var token = document.getElementById('token').getAttribute('content');

        var ajax = new objetoAjax();
        var datasend = new FormData();
        datasend.append('tag', tagValue);
        datasend.append('id_restaurant', id_restaurant);
        datasend.append('id_usuari', id_usuari);
        datasend.append('_token', token);
        ajax.open('POST', '../addTag', true);

        ajax.onreadystatechange = function() {
            if (ajax.status == 200 && ajax.readyState == 4) {
                msgTag.innerHTML = "Tag añadido!";
                tag.value = "";
                renderRestaurantTags();
            } else {
                msgTag.innerHTML = "Tag no añadido!";
            }
            setTimeout(function() {
                msgTag.innerHTML = "";
            }, 3000);
        }
        ajax.send(datasend);
    }
}

function eliminarTag(id_tag) {
    opcion = confirm("¿Está seguro de borrar el tag?");

    if (opcion == false) {
        event.preventDefault();
    } else {
        var token = document.getElementById('token').getAttribute('content');
        var msg = document.getElementById('msgTag');

        var ajax = new objetoAjax();
        var datasend = new FormData();
        datasend.append('_token', token);
        datasend.append('id_tag', id_tag);
        ajax.open('POST', '../eliminarTag', true);

        ajax.send(datasend);

        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                // Imprimimos en pantalla un mensaje para el usuario
                msg.innerHTML = "Tag borrado correctamente!";

                //Refrescamos los datos
                renderRestaurantTags();
            } else {
                msg.innerHTML = "Algo ha fallado!";
            }
            setTimeout(function() {
                msg.innerHTML = "";
            }, 3000);
        }
    }
}