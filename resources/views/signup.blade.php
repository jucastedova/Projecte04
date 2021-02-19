<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b2a65126dc.js" crossorigin="anonymous"></script>
    <title>Registro</title>
</head>
<body class="page-2 signup">
    <nav class="menu_nav">
        <div class="logo_nav"><a href="{{url('/')}}"><img src="{{asset('img/LogoProjecte04.png')}}" alt="logo geoeat"></a></div>
        <div class="registro_nav">
            <a href="{{url('login')}}"><i class="fas fa-home"></i>Inicia sesión</a>
        </div>
    </nav>

    <main>
        <h3>Registrate</h3>
        <div class="container">
            <form action="{{url('signup')}}" method="POST">
                {{csrf_field()}}
                <!-- First name -->
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="filledIn">
                </div>
                <!-- Last name -->
                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="filledIn">
                </div>
                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="filledIn">
                </div>
                <!-- Password -->
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="filledIn">
                </div>
                <!-- Password2 -->
                <div class="form-group">
                    <label for="password2">Repite la contraseña</label>
                    <input type="password" id="password2" name="password2" class="filledIn">
                </div>
                <input type="submit" value="Crear cuenta">
            </form>
        </div>
        @if ($errors->any())
            <div>
                <ul class="error">
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
</body>
</html>

        