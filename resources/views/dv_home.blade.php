<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title>Home | dv</title>
</head>

<body>
    <!-- <div id="map"></div> -->
    @if (session()->has('estandard'))
        <input type="hidden" value="1" id="filterEstandard" name="filterEstandard"> 
        <input type="hidden" value="{{ session()->get('userId') }}" id="userId" name="userId">
    @endif
    
    <!-- <form action="{{url('cerrarSesion')}}" method="GET">
        <button type="submit" class="btn btn-info">Cerrar sesión</button>
    </form> -->
    <!-- REVIEW -->
    <!-- Tener en cuenta que el menú cambia si está o no logueado. También paarecerá o no el cerrar sesión -->
    <!-- FIN RREVIEW -->
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
    <div class="row">
        <div class="column-1">
            <h1>Buscar restaurante</h1>
            <div class="container--filter">
                <div class="form-group">
                    <input class="form-control" type="text" id="search--rest" name="search--rest" placeholder="Buscar por restaurante..." onkeyup="searchRestaurants()">
                </div>
                <div class="logo-filtro">
                    <i class="fas fa-filter" onclick="openModal()"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- FILTRO https://fontawesome.com/icons/filter?style=solid -->
    <!-- Sección que vamos a recargar con AJAX -->
    <div class="row" id="section-3">
    </div>
    
    <div class="modal" id="modal-filter"> <!-- Modal filtre -->    
        <div class="modal-content">
            <div class="close--modal">
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form method="post" onsubmit="searchRestaurants(); return false;">
                <div>
                    <label for="precio_medio">Precio medio</label>
                    <input type="number" id="precio_medio" name="precio_medio">
                </div>
                <div>
                    <label for="valoracion">Valoración</label>
                    <input type="number" id="valoracion" name="valoracion" min="1" max="5">
                </div>
                    <!-- REVIEW -->
                    <!-- S'AGAFEN ELS VALORS DE LA BBDD -->
                    @foreach ($listCategories as $category)
                        <div class="container-tipo-cocina">
                            <label for="{{$category->Id_cuina}}">{{$category->Nom_cuina}}</label>
                            <input class="filtro--tipo_cocina" type="checkbox" id="{{$category->Id_cuina}}" name="{{$category->Id_cuina}}" value="{{$category->Nom_cuina}}">
                        </div>
                    @endforeach
                    <!-- END REVIEW -->
                <div>

                </div>
   
                <div class="form-btn">            
                    <div>
                        <input type="reset" value="Borrar todo">
                    </div>
                    <div>
                        <input type="submit" value="Aplicar">
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- END Modal filtre -->   

    <!-- Modal Map -->   
    <div id="modal-map" class="modal-map">
        <div class="close--modal-map">
            <span class="close--map" onclick="closeMapModal()">&times;</span>
        </div>
        <div class="container--map">
            <div id="map"></div>
        </div>
    </div>
    <!-- END Modal Map -->   
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
    <script src="{{asset('js/app.js')}}"></script>

    <script>
        var geocoder = L.esri.Geocoding.geocodeService();
        
        var map = L.map('map');
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        map.setView([51.505, -0.09], 13);
    </script>
</body>
</html>