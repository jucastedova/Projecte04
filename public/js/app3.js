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
                console.log(i)
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