<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <!-- BOOTSTRAP 5 -->
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <script src="https://kit.fontawesome.com/b2a65126dc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <!-- Load Leaflet from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
    integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
    crossorigin=""></script>
    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@2.3.2/dist/esri-leaflet.js"
    integrity="sha512-6LVib9wGnqVKIClCduEwsCub7iauLXpwrd5njR2J507m3A2a4HXJDLMiSZzjcksag3UluIfuW1KzuWVI5n/cuQ=="
    crossorigin=""></script>
    <!-- GEOCODER -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet.esri.geocoder/2.1.0/esri-leaflet-geocoder.css">
    <script src="https://cdn.jsdelivr.net/leaflet.esri.geocoder/2.1.0/esri-leaflet-geocoder.js"></script>
    <!-- END GEOCODER -->
    <!-- ROUTING -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
	<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <!-- END ROUTING -->
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <title>Restaurante</title>
</head>
<body class="pg-verRestaurante">
    <nav class="menu_nav">
        <div class="logo_nav"><a href="{{url('/')}}"><img src="{{asset('img/LogoProjecte04.png')}}" alt="logo geoeat"></a></div>
        @if (session()->has('admin') || session()->has('estandard'))
            <!-- Si inicia sessió -->
        <h2>{{ session()->get('userName') }}</h2>
        <form action="{{url('cerrarSesion')}}" method="GET">    
            <button type="submit" class="btn btn-info">Cerrar sesión</button>
        </form>
        @else
            <!-- Si no hi ha sessió iniciada -->
            <div class="registro_nav">
                <a href="{{url('login')}}"><i class="fas fa-home"></i>Iniciar sesión / Registro</a>
            </div>
        @endif
    </nav>
    <h1>{{$restaurant->Nom_restaurant}}</h1>

    <main class="container">
        <input type="hidden" name="id_restaurant" id="id_restaurant" value="{{$restaurant->Id_restaurant}}">
        <input type="hidden" name="id_usuari" id="id_usuari" value="{{ session()->get('userId') }}">
        <!-- <div class="datos_restaurante">     -->
        <div class="row">    
            <!-- <div class="container--infoRestaurante"> -->
            <div class="col-lg">
                <h2>Información</h2>
                <p>
                    <span class="bold">Tipo de comida:</span>
                    @foreach ($cocinas_seleccionadas as $cocina_seleccionada)
                        @if ($cocina_seleccionada === end($cocinas_seleccionadas))
                            <span>{{$cocina_seleccionada->Nom_cuina}}</span>
                        @else
                            <span>{{$cocina_seleccionada->Nom_cuina}} - </span>
                        @endif
                    @endforeach
                </p>
                <p><span class="bold">Dirección: </span>{{$restaurant->Adreca_restaurant}}</p>
                <p><span class="bold">Precio medio: </span>{{$restaurant->Preu_mitja_restaurant}}€</p>
                <p><span class="bold">Descripción:</span></p>
                <p>{{$restaurant->Descripcio_restaurant}}</p>
                {{-- <div class="container--progress">
                    <div class="capa-progress"></div>
                    <div id="progress" class="progress" style="width: calc({{$restaurant->Valoracio}} * 100%/5)"></div>;
                </div> --}}
            </div>
            <!-- <div class="container--imagenRestaurante"> -->
            <div class="col-lg">
                <div>
                    @foreach ($primeraImatge as $imatge)
                        <img src="../storage/{{$imatge->Ruta_Text_Imatge}}" style="width:80%" name="{{$imatge->id_imatge}}" id="{{$imatge->id_imatge}}" class="img-fluid">
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 margin-top">
                @if (session()->has('estandard'))
                <h2>Tu puntuación...</h2>
                <div>
                    <div class="fork fork1">
                        <div class="fork-inner" id="fork_1" onclick="puntuar('1')"></div>
                        <div class="fork fork2" >
                            <div class="fork-inner" id="fork_2" onclick="puntuar('2')"></div>
                            <div class="fork fork3">
                                <div class="fork-inner" id="fork_3" onclick="puntuar('3')"></div>
                                <div class="fork fork4">
                                    <div class="fork-inner" id="fork_4" onclick="puntuar('4')"></div>
                                    <div class="fork fork5">
                                        <div class="fork-inner" id="fork_5" onclick="puntuar('5')"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- TAGS -->
            <div class="col-lg-6 margin-top">
                @if (session()->has('estandard'))
                <!-- <div class="tags"> -->
                <input type="text" id="tag" placeholder="Escribe un tag..." onkeyup="añadirTag(event)">
                <div id="mostrarTags"></div>
                <p id="msgTag"></p>
                @endif
            </div>
        </div>
        
        <div class="row"> 
            <div class="col">
                <div class="container--map">
                    <div id="map" class="map--ver-restaurant"></div>
                </div>
                <div class="content-marker">
                    <i class="fas fa-map-marker-alt"></i>
                    <span onclick="calcRouteToRestaurant()" class="calc-route--verRest">Cómo llegar</span>
                </div>
            </div>       
        </div>
        
        <div>
            <h2>Opiniones</h2>
            @if (session()->has('estandard'))
            <div class="area_comentario">
                <textarea name="nuevo_comentario" id="nuevo_comentario" cols="30" rows="10"></textarea>
                <button class="btn--enviarComentario" onclick="enviarComentario()">Enviar</button>
            </div>
            </div>
            @endif
            <div class="content--comentarios" id="content--comentarios"></div>
        </div>
    </main>
    <footer>
        <div>
            <h3>Descubre Deliveroo</h3>
            <p>Quiénes somos</p>
            <p>Sala de prensa</p>
            <p>Empleo</p>
        </div>
        <div>
            <h3>Legal</h3>
            <p>Términos y condiciones</p>
            <p>Privacidad</p>
            <p>Cookies</p>
        </div>
        <div>
            <h3>Ayuda</h3>
            <p>Contacto</p>
            <p>Preguntas frecuentes</p>
            <p>Cocina</p>
        </div>
    </footer>
    <script src="{{asset('js/app1.js')}}"></script>
    <script src="{{asset('js/route.js')}}"></script>

    <script>
        var geocoder = L.esri.Geocoding.geocodeService();
        
        var map = L.map('map');
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var greenIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        
        geocoder.geocode()
        .address('{{$restaurant->Adreca_restaurant}}')
        .city('{{$restaurant->Ciutat_restaurant}}')
        .postal('{{$restaurant->CP_restaurant}}')
        .run(function (error, response) {
            if (error) {
                return;
            }
            map.fitBounds(response.results[0].bounds);
            map.setZoom(18);
            restMarker = L.marker(response.results[0].latlng, {icon: greenIcon});
            restMarker.addTo(map)
                .bindPopup(`<b>{{$restaurant->Adreca_restaurant}}</b>`)
                .openPopup();
            restLat = response.results[0].latlng.lat;
            restLong = response.results[0].latlng.lng;
        });
    </script>
    <!-- BOOTSTRAP 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    <!-- BOOTSTRAP 5 -->
</body>
</html>

