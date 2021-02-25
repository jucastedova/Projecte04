<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    
    <script src="https://kit.fontawesome.com/b2a65126dc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <title>Home | dv</title>
</head>

<body class="dv_admin">
    <!-- Tener en cuenta que el menú cambia si está o no logueado. También paarecerá o no el cerrar sesión -->
    <nav class="menu_nav">
        <div class="logo_nav"><a href="{{url('dv_admin')}}"><img src="{{asset('img/LogoProjecte04.png')}}" alt="logo geoeat"></a></div>
        @if (session()->has('admin'))
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
            <input type="hidden" value="1" id="filterAdmin" name="filterAdmin"> 
            <div class="container--filter">
                <div class="form-group">
                    <input class="form-control" type="text" id="search--rest" name="search--rest" placeholder="Buscar por restaurante..." onkeyup="searchRestaurantsAdmin()">
                </div>
                <div class="logo-filtro">
                    <i class="fas fa-filter" onclick="openModal()"></i>
                </div>
            </div>
            <div class="registros">
                <a href="{{url('registerRestaurantView')}}">Registrar restaurante</a>
                <a href="{{url('signupAdminView')}}">Registrar administrador</a>
                <a href="{{url('gestionarTagsAdmin')}}">Gestionar tags</a>
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
            <form method="post" onsubmit="searchRestaurantsAdmin(); return false;">
                <div class="container--precio-valoracion">                    
                    <div>
                        <label for="precio_medio" class="bold">Precio medio</label>
                        <input type="number" id="precio_medio" name="precio_medio">
                    </div>
                    <div>
                        <label for="valoracion" class="bold">Valoración</label>
                        <input type="number" id="valoracion" name="valoracion" min="1" max="5">
                    </div>
                </div>
                <!-- S'AGAFEN ELS VALORS DE LA BBDD -->
                <div class="container--tag-categorias"> 
                    <!-- TIPUS CUINA -->
                    <div>   
                        <p class="bold">Gastronomía</p>                 
                        @foreach ($listCuina as $tipus)
                        <div class="container-tipo-cocina">
                            <label for="{{$tipus->Id_cuina}}">{{$tipus->Nom_cuina}}</label>
                            <input class="filtro--tipo_cocina" type="checkbox" id="{{$tipus->Id_cuina}}" name="{{$tipus->Id_cuina}}" value="{{$tipus->Nom_cuina}}">
                        </div>
                        @endforeach
                    </div>               
                    <!-- CATEGORIES -->
                    <div> 
                        <p class="bold">Categoría</p>
                        @foreach ($listCategories as $cat)
                        <div class="container-tipo-cocina">
                            <label for="{{$cat->Id_categoria}}">{{$cat->Nom_categoria}}</label>
                            <input class="filtro--tipo_categoria" type="checkbox" id="categoria{{$cat->Id_categoria}}" name="categoria{{$cat->Id_categoria}}" value="{{$cat->Nom_categoria}}">
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-btn">
                    <div>
                        <input type="reset" value="Borrar todo">
                    </div>
                    <div>
                        <input type="submit" value="Aplicar" id="btn--applicar-filtro">
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- END Modal filtre -->

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