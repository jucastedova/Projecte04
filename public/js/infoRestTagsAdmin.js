window.onload = function() {
    renderRestaurantTagsAdmin();
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

function renderCategorias() {
    //Recogemos variables de la pagina
    section = document.getElementById('tbodyGestorAdmin');
    token = document.getElementById('token').getAttribute('content');
    renderedResults = '';
    //Ajax
    var ajax = new objetoAjax();
    ajax.open('POST', '../getCategorias', true);
    var datasend = new FormData();
    datasend.append('_token', token);
    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            var respuesta = JSON.parse(ajax.responseText);

            if (respuesta.length == 0) {
                renderedResults = "No hay tags.";
            }

            for (let i = 0; i < respuesta.length; i++) {
                renderedResults += '<tr>'
                renderedResults += '<td>' + respuesta[i].Id_tag + '</td>' + '<td>' + respuesta[i].Nom_tag + '</td>';
                renderedResults += '<td>';
                renderedResults += '<button onclick="eliminarCategorias(' + respuesta[i].Id_tag + ')">Eliminar</button>';
                renderedResults += '<button onclick="modificarTag(' + respuesta[i].Id_tag + ')">Modificar</button>';
                renderedResults += '</td>';
                renderedResults += '</tr>'
            }

            section.innerHTML = renderedResults;
        } else {
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }
    }
    ajax.send(datasend);
}

function añadirCategorias(e) {
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
                console.log('App::Problems on comentarios request: ' + ajax.statusText);
            }
            setTimeout(function() {
                msgTag.innerHTML = "";
            }, 3000);
        }
        ajax.send(datasend);
    }
}

function eliminarCategorias(id_tag) {
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
                renderCategorias();
            } else {
                msg.innerHTML = "Algo ha fallado!";
            }
            setTimeout(function() {
                msg.innerHTML = "";
            }, 3000);
        }
    }
}