<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <script src="https://kit.fontawesome.com/b2a65126dc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
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
        <input type="text" id="adreca_restaurant" name="adreca_restaurant" placeholder="Dirección restaurante" ><br><br>
        <input type="number" id="preu_mitja" step="any" name="preu_mitja" placeholder="Precio medio" min="5" max="5000" ><br><br>
        <input type="email" id="correu_gerent" name="correu_gerent" placeholder="Correo gerente" ><br><br>
        <label for="tipo_cocina">Tipo cocina:</label><br><br>
        @foreach ($listCategories as $category)
        <div class="container-tipo-cocina">
            <label for="{{$category->Id_cuina}}">{{$category->Nom_cuina}}</label><br><br>
            <input class="filtro--tipo_cocina" type="checkbox" id="tiposCocinas[]" name="tiposCocinas[]" value="{{$category->Nom_cuina}}">
        </div>
        @endforeach
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