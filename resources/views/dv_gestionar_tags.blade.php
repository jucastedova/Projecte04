<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <script src="https://kit.fontawesome.com/b2a65126dc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <!-- Load Leaflet from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>
    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@2.3.2/dist/esri-leaflet.js" integrity="sha512-6LVib9wGnqVKIClCduEwsCub7iauLXpwrd5njR2J507m3A2a4HXJDLMiSZzjcksag3UluIfuW1KzuWVI5n/cuQ==" crossorigin=""></script>
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
    @if (session()->has('estandard'))
    <input type="hidden" value="1" id="filterEstandard" name="filterEstandard">
    <input type="hidden" value="{{ session()->get('userId') }}" id="userId" name="userId">
    @endif

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
    
    <div class="gestorCategorias">
        <table id="gestorTable">
            <tr>
                <th>ID</th>
                <th>Categoria</th>
                <th>Opciones</th>
            </tr>
            <tbody id="tbodyGestorAdmin">

            </tbody>
        </table>
        <div class="input">
            <input type="text" id="cat" placeholder="Añade una categoria..." onkeyup="añadirCategoria(event)">
        </div>
        <p id="msgTag" class="display-none"></p>
    </div>

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

    <script src="{{asset('js/infoRestTagsAdmin.js')}}"></script>
</body>

</html>