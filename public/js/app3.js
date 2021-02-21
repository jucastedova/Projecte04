window.onload = function() {
    renderTags();
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

//  Tags
function renderTags() {
    //Recogemos variables de la pagina
    var section = document.getElementById('bodyTable');
    var token = document.getElementById('token').getAttribute('content');
    var idUsuario = document.getElementById('idUsuario').value;
    var renderedResults = '';

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
                renderedResults += '<tr>';
                renderedResults += '<td>' + respuesta[i].Id_tag + '</td>';
                renderedResults += '<td>' + respuesta[i].Nom_tag + '</td>';
                renderedResults += '</tr>';
            }
            section.innerHTML = renderedResults;
        } else {
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }
    }
    ajax.send(datasend);
}

function eliminarTag(id_tag) {
    opcion = confirm("¿Está seguro de borrar el tag?");

    if (opcion == false) {
        event.preventDefault();
    } else {
        var token = document.getElementById('token').getAttribute('content');
        var msg = document.getElementById('msg');

        var ajax = new objetoAjax();
        var datasend = new FormData();
        datasend.append('_token', token);
        datasend.append('id_tag', id_tag);
        ajax.open('POST', 'eliminarTag', true);

        ajax.send(datasend);

        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                // Imprimimos en pantalla un mensaje para el usuario
                msg.innerHTML = "Tag borrado correctamente!";

                //Refrescamos los datos
                renderTags();
            } else {
                msg.innerHTML = "Algo ha fallado!";
            }
            setTimeout(function() {
                msg.innerHTML = "";
            }, 3000);
        }
    }
}

function enviarComentario() {
    let comentario = document.getElementById('nuevo_comentario').value;
    let id_restaurant = document.getElementById('id_restaurant').value;
    let id_usuari = document.getElementById('id_usuari').value;
    // console.log(`Comentario: ${comentario}`);
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
            console.log('valoracion usuario', valoracion);
            // console.log('App::Response valoracion:', respuesta);
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
    // let puntuacion = document.getElementById('puntuar').value;
    let id_restaurant = document.getElementById('id_restaurant').value;
    let id_usuari = document.getElementById('id_usuari').value;
    // console.log('puntuacion: ', puntuacion);
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