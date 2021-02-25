<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b2a65126dc.js" crossorigin="anonymous"></script>
    <title>Login</title>
</head>
<body class="page-1 login">
    <nav class="menu_nav">
        <div class="logo_nav"><a href="{{url('/')}}"><img src="{{asset('img/LogoProjecte04.png')}}" alt="logo geoeat"></a></div>

        <div class="registro_nav">
            <a href="{{url('signupView')}}"><i class="fas fa-user-plus"></i>Registrate</a>
        </div>
    </nav>
    
    <main>
        <h3>Iniciar sesión</h3>
        <div class="container">
            <form action="{{url('loginUser')}}" method="POST">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="gmail" for="email">Email</label>
                    <input class="form-control" type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="pwd">Contraseña</label>
                    <input class="form-control" type="password" id="pwd" name="pwd">
                </div>
                <div class="bt_submit"><input type="submit" class="btn btn-outline-dark" value="Continuar" name="continuar"></div>
            </form>
            @if (session('error'))
                <div class="error">
                    <p>Usuario o contraseña incorrecto</p>
                </div>
            @endif
            @if ($errors->any())
            <div>
                <ul class="error">
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        </div>
    </main>
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
</body>
</html>