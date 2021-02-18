<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <script src="https://kit.fontawesome.com/b2a65126dc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <title>Restaurante</title>
</head>
<body class="pg-verRestaurante">
    <nav class="menu_nav">
        <div class="logo_nav"><a href="{{url('/')}}"><img src="{{asset('img/logo-teal.svg')}}" alt="logo deliberoo"></a></div>
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
    <h1>VER RESTAURANTE</h1>

    <main>
        <input type="hidden" name="id_restaurant" id="id_restaurant" value="{{$restaurant->Id_restaurant}}">
        <input type="hidden" name="id_usuari" id="id_usuari" value="{{ session()->get('userId') }}">
        <div class="datos_restaurante">    
            <div class="container--infoRestaurante">
                <h2>{{$restaurant->Nom_restaurant}}</h2>
                <p>
                    @foreach ($cocinas_seleccionadas as $cocina_seleccionada)
                        @if ($cocina_seleccionada === end($cocinas_seleccionadas))
                            <span>{{$cocina_seleccionada->Nom_cuina}}</span>
                        @else
                            <span>{{$cocina_seleccionada->Nom_cuina}} - </span>
                        @endif
                    @endforeach
                </p>
                <p>{{$restaurant->Adreca_restaurant}}</p>
                <p>Precio medio: {{$restaurant->Preu_mitja_restaurant}}€</p>
                <p>Descripción:</p>
                <p>{{$restaurant->Descripcio_restaurant}}</p>
                {{-- <div class="container--progress">
                    <div class="capa-progress"></div>
                    <div id="progress" class="progress" style="width: calc({{$restaurant->Valoracio}} * 100%/5)"></div>;
                </div> --}}
            </div>
            <div class="container--imagenRestaurante">
                <div>
                    @foreach ($primeraImatge as $imatge)
                        <img src="data:image/png;base64,{{ chunk_split(base64_encode($imatge->Ruta_Imatge)) }}" style="width:80%" name="{{$imatge->id_imatge}}" id="{{$imatge->id_imatge}}">
                    @endforeach
                </div>
            </div>
        </div>
        <!-- REVIEW -->
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
        @endif
        <!-- END REVIEW -->
        <div class="container--comentarios">
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
</body>
</html>

