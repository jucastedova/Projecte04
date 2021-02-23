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

function displayMsg() {
    msgTag.classList.remove('display-none');
    msgTag.classList.add('msgTagDisplay');
}

function removeMsg() {
    msgTag.classList.remove('msgTagDisplay');
    msgTag.classList.add('display-none');
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
                renderedResults += '<div class="modal-cat" id="modal-cat' + respuesta[i].Id_categoria + '">';
                renderedResults += '<div class="modal-content-cat">';
                renderedResults += '<div class="close-modal-cat">';
                renderedResults += '<span class="title-cat">Modificar categoria</span>';
                renderedResults += '<span class="close-cat" onclick="closeModalCat(' + respuesta[i].Id_categoria + ')">&times;</span>';
                renderedResults += '</div>';
                renderedResults += '<div class="form-modal-cat">';
                renderedResults += '<div class="containerFlex">';
                renderedResults += '<div class="labelCat">';
                renderedResults += '<label for="Id_categoria">ID</label><br>';
                renderedResults += '<input type="text" class="inputCat" id="Id_categoria' + respuesta[i].Id_categoria + '" value="' + respuesta[i].Id_categoria + '" readonly><br>';
                renderedResults += '</div>';
                renderedResults += '<div class="labelCat">';
                renderedResults += '<label for="Nombre_categoria">Categoria</label><br>';
                renderedResults += '<input type="text" class="inputCat" id="Nombre_categoria' + respuesta[i].Id_categoria + '" value="' + respuesta[i].Nom_categoria + '">';
                renderedResults += '</div>';
                renderedResults += '</div>';
                renderedResults += '<div class="containerFlex">';
                renderedResults += '<button class="btn btn-info btn-margin" onclick="actualizarCategoria(' + respuesta[i].Id_categoria + ')">Actualizar</button>';
                renderedResults += '</div>';
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
            var respuesta = JSON.parse(ajax.responseText);
            if (respuesta == "OK") {
                closeModalCat(id);
                displayMsg();
                renderCategorias();
                msgTag.innerHTML = "Categoria actualizada!";
            } else {
                closeModalCat(id);
                displayMsg();
                msgTag.innerHTML = "Error: Categoria existente!";
            }

        } else {
            displayMsg();
            msgTag.innerHTML = "Error!";
        }

        setTimeout(function() {
            removeMsg();
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
            console.log(ajax.status + " " + ajax.readyState);
            if (ajax.status == 200 && ajax.readyState == 4) {
                var respuesta = JSON.parse(ajax.responseText);
                if (respuesta == "OK") {
                    displayMsg();
                    msgTag.innerHTML = "Categoria añadida!";
                    renderCategorias();
                } else {
                    displayMsg();
                    msgTag.innerHTML = "Error: Categoria existente!";
                }
            } else {
                displayMsg();
                msgTag.innerHTML = "Error!";
            }

            tag.value = "";

            setTimeout(function() {
                removeMsg()
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
                var respuesta = JSON.parse(ajax.responseText);

                if (respuesta == "OK") {
                    displayMsg();
                    // Imprimimos en pantalla un mensaje para el usuario
                    msg.innerHTML = "Categoria borrada!";
                    //Refrescamos los datos
                    renderCategorias();
                } else {
                    displayMsg()
                        // Imprimimos en pantalla un mensaje para el usuario
                    msg.innerHTML = "Error: Categoria en uso!";
                    //Refrescamos los datos
                    renderCategorias();
                }
            } else {
                displayMsg();
                msg.innerHTML = "Algo ha fallado!";
            }
            setTimeout(function() {
                removeMsg();
                msg.innerHTML = "";
            }, 3000);
        }
    }
}