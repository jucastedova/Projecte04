<!DOCTYPE html>
<html lang="es">
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
    <title>Registrar restaurante</title>
</head>
<body class="pg-register">
    <nav class="menu_nav">
        <div class="logo_nav">
            <a href="{{url('dv_admin')}}">
                <img src="{{asset('img/LogoProjecte04.png')}}" alt="logo geoeat">
            </a>
        </div>
            <!-- Si inicia sessió -->
            <form action="{{url('cerrarSesion')}}" method="GET">    
                <button type="submit" class="btn btn-info">Cerrar sesión</button>
            </form>
    </nav>
    <h1>Crear restaurante</h1>
    <form action="{{url('crearRestaurante')}}" method="POST" enctype="multipart/form-data" class="form-crear">
        @csrf
        <input type="hidden" name="userId" id="userId" value="{{ session()->get('userId') }}">
        <input type="text" id="nom_restaurant" name="nom_restaurant" placeholder="Nombre restaurante"><br><br>
        <input type="text" id="Ciutat_restaurant" name="Ciutat_restaurant" placeholder="Ciudad restaurante" ><br><br>
        <input type="text" id="CP_restaurant" name="CP_restaurant" placeholder="Código postal restaurante" ><br><br>
        <input type="text" id="adreca_restaurant" name="adreca_restaurant" placeholder="Dirección restaurante" ><br><br>
        <div class="container--map display-none" id="container-map">
            <div id="map" class="map--create-modify"></div>
        </div>
        <p class="error" id="error-address"></p>
        <input type="number" id="preu_mitja" step="any" name="preu_mitja" placeholder="Precio medio" min="5" max="5000" ><br><br>
        <input type="email" id="correu_gerent" name="correu_gerent" placeholder="Correo gerente" ><br><br>
        <label for="tipo_cocina">Tipo cocina:</label>
        @foreach ($listCuina as $tipus)
        <div class="container-tipo-cocina">
            <label for="{{$tipus->Id_cuina}}">{{$tipus->Nom_cuina}}</label><br><br>
            <input class="filtro--tipo_cocina" type="checkbox" id="tiposCocinas[]" name="tiposCocinas[]" value="{{$tipus->Nom_cuina}}">
        </div>
        @endforeach<br>
        <label for="tipo_cocina">Categoria:</label>
        @foreach ($listCategories as $cat)
        <div class="container-tipo-cocina">
            <label for="{{$cat->Id_categoria}}">{{$cat->Nom_categoria}}</label><br><br>
            <input class="filtro--tipo_cocina" type="checkbox" id="tiposCategoria[]" name="tiposCategoria[]" value="{{$cat->Nom_categoria}}">
        </div>
        @endforeach<br>
        <textarea name="descripcio_restaurant" id="descripcio_restaurant" placeholder="Descripción"></textarea><br><br>
        <input type="file" name="imatge" id="imatge" accept="image/png"><br><br>
        <input type="submit" value="Crear restaurante" id="submit--control">
        <div id="error--checked">
        </div>
    </form> 
    @if ($errors->any())
        <div class="errores">
            <ul class="error">
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
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
    <script src="{{asset('js/app2.js')}}"></script>
</body>
</html>