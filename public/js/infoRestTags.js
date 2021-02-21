window.onload = function() {
    renderRestaurantTags();
}

function renderRestaurantTags() {
    //Recogemos variables de la pagina
    var section = document.getElementById('mostrarTags');
    var token = document.getElementById('token').getAttribute('content');
    var idUsuario = document.getElementById('id_usuari').value;
    var id_restaurant = document.getElementById('id_restaurant').value;
    var renderedResults = '';

    //Ajax
    var ajax = new objetoAjax();
    ajax.open('POST', 'getRestaurantTags', true);
    var datasend = new FormData();
    datasend.append('_token', token);
    datasend.append('idUsuario', idUsuario);
    datasend.append('id_restaurant', id_restaurant);
    ajax.onreadystatechange = function() {
        if (ajax.status == 200 && ajax.readyState == 4) {
            var respuesta = JSON.parse(ajax.responseText);
            for (let i = 0; i < respuesta.length; i++) {
                renderedResults += '<p>' + respuesta[i].Nom_tag + '<i class="fa fa-times" aria-hidden="true"></i></p>';
            }
            section.innerHTML = renderedResults;
        } else {
            console.log('App::Problems on comentarios request: ' + ajax.statusText);
        }
    }
    ajax.send(datasend);
}

function añadirTag(e) {
    e.which = e.which || e.keyCode;
    if (e.which == 13) {
        tag = document.getElementById('tag').value;
        id_restaurant = document.getElementById('id_restaurant').value;
        id_usuari = document.getElementById('id_usuari').value;
        msgTag = document.getElementById('msgTag');
        var token = document.getElementById('token').getAttribute('content');

        var ajax = new objetoAjax();
        var datasend = new FormData();
        datasend.append('tag', tag);
        datasend.append('id_restaurant', id_restaurant);
        datasend.append('id_usuari', id_usuari);
        datasend.append('_token', token);
        ajax.open('POST', '../addTag', true);

        ajax.onreadystatechange = function() {
            if (ajax.status == 200 && ajax.readyState == 4) {
                // Imprimimos en pantalla un mensaje para el usuario
                msgTag.innerHTML = "Tag añadido!";
                renderRestaurantTags()
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