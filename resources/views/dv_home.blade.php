<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <!-- <link rel="preconnect" href="https://fonts.gstatic.com"> -->
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    
    <script src="https://kit.fontawesome.com/b2a65126dc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <title>Home | dv</title>
</head>

<body>
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
                    <a href="/dv_tags" class="btn btn-info">Ver tags</a>
                </div>
            </div>
        </div>
    </div>
    <!-- FILTRO https://fontawesome.com/icons/filter?style=solid -->
    <!-- Sección que vamos a recargar con AJAX -->
    <div class="row" id="section-3">
    </div>
    
    <div class="modal" id="modal-filter">    
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
    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>