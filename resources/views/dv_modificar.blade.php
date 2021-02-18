<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <script src="https://kit.fontawesome.com/b2a65126dc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <title>Modificar</title>
</head>
<body class="pg-modificar">
    <nav class="menu_nav">
        <div class="logo_nav"><a href="{{url('dv_admin')}}"><img src="{{asset('img/logo-teal.svg')}}" alt="logo deliberoo"></a></div>
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
    <h1>MODIFICAR RESTAURANTE</h1>

    <div>
        <form action="{{url('actualizarRestaurante')}}" method="POST" enctype="multipart/form-data" class="form-modificar">
            @csrf
            {{method_field('PUT')}}
            <input type="hidden" name="userId" id="userId" value="{{ session()->get('userId') }}">
            <input type="hidden" id="Id_restaurant" name="Id_restaurant" value="{{$restaurant->Id_restaurant}}">
            <input type="hidden" id="destinatario" name="destinatario" value="{{$restaurant->Correu_gerent_restaurant}}">
            <input type="hidden" id="destinatario" name="nom_gerent" value="{{$restaurant->Nom_gerent_restaurant}}">
            <div>
                <label>Nombre del restaurante</label><br>
                <input type="text" id="Nom_restaurant" name="Nom_restaurant" value="{{$restaurant->Nom_restaurant}}" >
            </div><br>
            <div>
                <label>Dirección</label><br>
                <input type="text" id="Adreca_restaurant" name="Adreca_restaurant" value="{{$restaurant->Adreca_restaurant}}" required>
            </div><br>

            <div>
                <label>Precio</label><br>
                <input type="text" id="Preu_mitja_restaurant" name="Preu_mitja_restaurant" value="{{$restaurant->Preu_mitja_restaurant}}" required>
            </div><br>

            <div>
                <label>Correo del gerente</label><br>
                <input type="email" id="Correu_gerent_restaurant" name="Correu_gerent_restaurant" value="{{$restaurant->Correu_gerent_restaurant}}" required>
            </div><br>

            <div>
                <label>Descripción</label><br>
                <textarea id="Descripcio_restaurant" name="Descripcio_restaurant">{{$restaurant->Descripcio_restaurant}}</textarea>
            </div>

            @php
                $trobat = false;
            @endphp
                @foreach ($lista_cuines as $cuina)
                        @php
                            $trobat = false;
                        @endphp
                    @foreach ($cocinas_seleccionadas as $cocina_seleccionada)
                        @if ($cocina_seleccionada->id_cuina == $cuina->Id_cuina)
                            @php
                                $trobat = true;
                            @endphp
                        @endif
                    @endforeach 
                    @if ($trobat)
                        <div class="container-tipo-cocina">
                            <label for="{{$cuina->Id_cuina}}">{{$cuina->Nom_cuina}}</label>
                            <input class="filtro--tipo_cocina" type="checkbox" id="tiposCocinas[]" name="tiposCocinas[]" value="{{$cuina->Nom_cuina}}" checked>
                        </div>
                    @else
                        <div class="container-tipo-cocina">
                            <label for="{{$cuina->Id_cuina}}">{{$cuina->Nom_cuina}}</label>
                            <input class="filtro--tipo_cocina" type="checkbox" id="tiposCocinas[]" name="tiposCocinas[]" value="{{$cuina->Nom_cuina}}">
                        </div> 
                    @endif
                @endforeach 

                @foreach ($primeraImatge as $imatge)
                <div>
                    <img src="data:image/png;base64,{{ chunk_split(base64_encode($imatge->Ruta_Imatge)) }}" style="width:80%" name="{{$imatge->id_imatge}}" id="{{$imatge->id_imatge}}">
                </div>
                @endforeach 
                <input type="file" name="imatge" id="imatge" accept="image/png"><br><br>
                <input type="submit" class="btn btn-outline-dark" value="continuar" name="continuar" id="submit--control">
                <div id="error--checked">
                </div>
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
    <script src="{{asset('js/app2.js')}}"></script>
</body>
</html>
