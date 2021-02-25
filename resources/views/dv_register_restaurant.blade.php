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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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
    @if ($errors->any())
        <div class="errores">
            <ul class="error">
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{url('crearRestaurante')}}" onsubmit="validarMap(event);" method="POST" enctype="multipart/form-data" class="form-crear">
        @csrf
        <input type="hidden" name="userId" id="userId" value="{{ session()->get('userId') }}">
        <div class="wc--input">
            <!-- NOM RESTAURANT -->
            <input type="text" id="Nom_restaurant" name="Nom_restaurant" placeholder="Nombre restaurante">
            <!-- CIUTAT -->
            <input type="text" id="Ciutat_restaurant" name="Ciutat_restaurant" placeholder="Ciudad restaurante">    
        </div>
        <div class="wc--input">        
            <!-- CODI POSTAL -->
            <input type="text" id="CP_restaurant" name="CP_restaurant" placeholder="Código postal restaurante">
            <!-- ADREÇA -->
            <input type="text" id="Adreca_restaurant" name="Adreca_restaurant" placeholder="Dirección restaurante">
        </div>
        <!-- MAPA -->
        <div class="container--map display-none" id="container-map">
            <div id="map" class="map--create-modify"></div>
        </div>
        <p class="error" id="error-address"></p>
        <div class="wc--input">        
            <!-- PREU MITJÀ -->
            <input type="number" id="Preu_mitja_restaurant" step="any" name="Preu_mitja_restaurant" placeholder="Precio medio" min="5" max="5000" >
            <!-- EMAIL GERENT -->
            <input type="email" id="Correu_gerent_restaurant" name="Correu_gerent_restaurant" placeholder="Correo gerente" >
        </div>
        <div class="wc--cat-cocina">        
            <!-- TIPUS CUINA -->
            <div>
                <p class="bold">Gastronomía</p>
                @foreach ($listCuina as $tipus)
                <div class="container-tipo-cocina">
                    <label for="{{$tipus->Id_cuina}}">{{$tipus->Nom_cuina}}</label>
                    <input class="filtro--tipo_cocina" type="checkbox" id="Tipos_Cocinas[]" name="Tipos_Cocinas[]" value="{{$tipus->Nom_cuina}}">
                </div>
                @endforeach
            </div>
            <!-- CATEGORIA -->
            <div>
                <p class="bold">Categoría</p>
                @foreach ($listCategories as $cat)
                <div class="container-tipo-cocina">
                    <label for="{{$cat->Id_categoria}}">{{$cat->Nom_categoria}}</label>
                    <input class="filtro--tipo_cocina" type="checkbox" id="Tipos_Categorias[]" name="Tipos_Categorias[]" value="{{$cat->Nom_categoria}}">
                </div>
                @endforeach
            </div>
        </div>
        <!-- DESCRIPCIÓ -->
        <textarea name="Descripcio_restaurant" id="Descripcio_restaurant" placeholder="Descripción"></textarea>
        <!-- FILE -->
        <div class="row container-file">
            <input type="file" name="imatge" id="imatge" accept="image/png">
        </div>
        <!-- SUBMIT -->
        <div class="wc--continuar">
            <input type="submit" value="Crear restaurante" id="submit--control">
        </div>
        <div id="error--checked">
        </div>
    </form> 
    <footer>
        <div>
            <h3>Descubre GeoEat</h3>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
</body>
</html>