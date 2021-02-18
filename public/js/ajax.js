window.onload = function() {
    // modal = document.getElementById('addImage');
    read();
    // alert("hola");
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

/* Muestra todos los registros de la base de datos (sin filtrar y filtrados) */
function read() {
    var section = document.getElementById('section-3');
    //var buscador = document.getElementById('searchPokemon').value;
    var ajax = new objetoAjax();
    var token = document.getElementById('token').getAttribute('content');
    // Busca la ruta read y que sea asyncrono
    ajax.open('post', 'read', true);
    var datasend = new FormData();
    //datasend.append('filtro', buscador);
    datasend.append('_token', token);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var respuesta = JSON.parse(ajax.responseText);
            var tabla = '';
            for (let i = 0; i < respuesta.length; i++) {
                //const element = array[i];
                    tabla += '<div>';
                    tabla += '<h4>' + respuesta[i].Nom_restaurant + '</h4>';
                    tabla += '<h4>' + respuesta[i].Preu_mitja_restaurant + '</h4>';
                    tabla += '<h4>' + respuesta[i].Adreca_restaurant + '</h4>';
                    tabla += '<h4>' + respuesta[i].Descripcio_restaurant + '</h4>';
                    tabla += '<h4>' + respuesta[i].Nom_cuina + '</h4>';
                    tabla += '<img src="data:image/png;base64,' + respuesta[i].Ruta_Imatge + '" alt="error" with="100%" height="60%"></img>';
                    tabla += '</div>';
                    console.log("hola")
                    section.innerHTML = tabla;
            }
        }else{
            console.log("adios")
        }
    }
    ajax.send(datasend);
}