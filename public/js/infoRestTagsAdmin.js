window.onload = function() {
    renderCategorias();
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

function openModalCat(id) {
    modalCategoria = document.getElementById('modal-cat' + id);
    modalCategoria.style.display = "block";
}

function closeModalCat(id) {
    modalCategoria = document.getElementById('modal-cat' + id);
    modalCategoria.style.display = "none";
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

            console.info(respuesta)

            for (let i = 0; i < respuesta.length; i++) {
                renderedResults += '<tr>';
                renderedResults += '<td>' + respuesta[i].Id_categoria + '</td>' + '<td>' + respuesta[i].Nom_categoria + '</td>';
                renderedResults += '<td>';
                renderedResults += '<button class="btn btn-info" onclick="eliminarCategorias(' + respuesta[i].Id_categoria + ')">Eliminar</button>';
                renderedResults += '<button class="btn btn-info" onclick="openModalCat(' + respuesta[i].Id_categoria + ')">Modificar</button>';
                renderedResults += '</td>';
                renderedResults += '</tr>';
                renderedResults += '<div class="modal-tag" id="modal-cat' + respuesta[i].Id_categoria + '">';
                renderedResults += '<div class="modal-content-tag">';
                renderedResults += '<div class="close-modal-tag">';
                renderedResults += '<span class="title-tag">Modificar categoria</span>';
                renderedResults += '<span class="close-tag" onclick="closeModalCat(' + respuesta[i].Id_categoria + ')">&times;</span>';
                renderedResults += '</div>';
                renderedResults += '<div class="form-modal-tag">';
                renderedResults += '<input type="text" id="Id_categoria' + respuesta[i].Id_categoria + '" value="' + respuesta[i].Id_categoria + '" readonly>';
                renderedResults += '<input type="text" id="Nombre_categoria' + respuesta[i].Id_categoria + '" value="' + respuesta[i].Nom_categoria + '">';
                renderedResults += '<button class="btn btn-info" onclick="actualizarCategoria(' + respuesta[i].Id_categoria + ')">Actualizar</button>';
                renderedResults += '</div>';
                renderedResults += '</div>';
                renderedResults += '</div>';
            }

            section.innerHTML = renderedResults;
        } else {
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }
    }
    ajax.send(datasend);
}

function actualizarCategoria(id) {
    Nombre_categoria = document.getElementById('Nombre_categoria' + id).value;
    msgTag = document.getElementById('msgTag');
    var token = document.getElementById('token').getAttribute('content');

    var ajax = new objetoAjax();
    var datasend = new FormData();
    datasend.append('Nombre_categoria', Nombre_categoria);
    datasend.append('Id_categoria', id);
    datasend.append('_token', token);
    ajax.open('POST', '../updateCategoria', true);

    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            msgTag.innerHTML = "Categoria actualizada!";
            closeModalCat(id);
            renderCategorias();
        } else {
            msgTag.innerHTML = "Categoria no actualizada!";
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }
        setTimeout(function() {
            msgTag.innerHTML = "";
        }, 3000);
    }
    ajax.send(datasend);

}

function añadirCategoria(e) {
    e.which = e.which || e.keyCode;
    if (e.which == 13) {
        tagValue = document.getElementById('cat').value;
        tag = document.getElementById('cat');
        msgTag = document.getElementById('msgTag');
        var token = document.getElementById('token').getAttribute('content');

        var ajax = new objetoAjax();
        var datasend = new FormData();
        datasend.append('cat', tagValue);
        datasend.append('_token', token);
        ajax.open('POST', '../addCategoria', true);

        ajax.onreadystatechange = function() {
            if (ajax.status == 200 && ajax.readyState == 4) {
                msgTag.innerHTML = "Categoria añadida!";
                tag.value = "";
                renderCategorias();
            } else {
                msgTag.innerHTML = "Categoria no añadida!";
                console.log('App::Problems on comentarios request: ' + ajax.statusText);
            }
            setTimeout(function() {
                msgTag.innerHTML = "";
            }, 3000);
        }
        ajax.send(datasend);
    }
}

function eliminarCategorias(id_cat) {
    opcion = confirm("¿Está seguro de borrar el tag?");

    if (opcion == false) {
        event.preventDefault();
    } else {
        var token = document.getElementById('token').getAttribute('content');
        var msg = document.getElementById('msgTag');

        var ajax = new objetoAjax();
        var datasend = new FormData();
        datasend.append('_token', token);
        datasend.append('id_cat', id_cat);
        ajax.open('POST', '../eliminarCategoria', true);

        ajax.send(datasend);

        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                // Imprimimos en pantalla un mensaje para el usuario
                msg.innerHTML = "Categoria borrada correctamente!";

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