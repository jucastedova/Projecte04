<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use App\Http\Requests\RestaurantRegisterRequest;
use App\Http\Requests\RestaurantModifyRequest;
use Illuminate\Support\Collection;
use App\Mail\EnviarCorreoGerente;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class RestauranteController extends Controller
{

    public function read(){
        $restaurantes = DB::select("SELECT tbl_cuina.*, tbl_restaurant.*, tbl_restaurant.*, tbl_imatge.* FROM tbl_cuina INNER JOIN tbl_tipus_cuina ON tbl_cuina.Id_cuina = tbl_tipus_cuina.Id_cuina INNER JOIN tbl_restaurant ON tbl_tipus_cuina.Id_restaurant = tbl_restaurant.Id_restaurant INNER JOIN tbl_imatge ON tbl_restaurant.Id_restaurant = tbl_imatge.Id_restaurant");
        
        foreach($restaurantes as $restaurante){
            if($restaurante->Ruta_Imatge != null){
                $restaurante->Ruta_Imatge = base64_encode($restaurante->Ruta_Imatge);
            }
        }
    }

    public function crearRestaurante(RestaurantRegisterRequest $request){
        $datos=$request->except('_token', 'Crear');
        try {
            DB::beginTransaction();
            $data = DB::table('tbl_restaurant')->insertGetId(['Nom_restaurant'=>$datos['nom_restaurant'],'Adreca_restaurant'=>$datos['adreca_restaurant'],'Preu_mitja_restaurant'=>$datos['preu_mitja'], 'Correu_gerent_restaurant'=>$datos['correu_gerent'], 'Descripcio_restaurant'=>$datos['descripcio_restaurant']]);
            
            $tipos_cocinas = $datos['tiposCocinas'];
    
            foreach($tipos_cocinas as $tipoCocina){
                $cocinas = DB::table('tbl_cuina')
                ->where([['Nom_cuina','=',$tipoCocina]])->get();
                foreach($cocinas as $cocina){
                    DB::table('tbl_tipus_cuina')->insertGetId(['Id_restaurant'=>$data, 'Id_cuina'=>$cocina->Id_cuina]);
                }
            }
    
            // Metodo antiguo de almacenamiento para que no pete
            $img = $request->file('imatge')->getRealPath();
            $bin = file_get_contents($img);
    
            // 'uploads' es la carpeta que crea, donde se almacenan las fotos public>uploads.
            $datos['imatge']=$request->file('imatge')->store('uploads','public');
            
            // El ID del user se debe colocar bindeando.
            DB::table('tbl_imatge')->insertGetId(['Id_restaurant'=>$data, 'Id_usuari'=>$datos['userId'], 'Ruta_imatge'=>$bin, 'Ruta_Text_Imatge'=>$datos['imatge'],
            'Titol'=>$datos['nom_restaurant']]);
            
            DB::commit();
            return redirect('/');

        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th;
        }

    }

    public function modificarRestauranteDatos($id){
        //Recoger los datos de la BBDD del registro que existe en base al id. 
        if(!session()->has('admin')) {
            return redirect('login');
        }
        try {
            DB::beginTransaction();
            //Método para actualizar restaurantes
            $restaurant=DB::table('tbl_restaurant')->WHERE('Id_restaurant','=', $id)->first(); 
            $lista_cuines = DB::table('tbl_cuina')->get();
            $cocinas_seleccionadas = DB::select("SELECT t.id_tipus_cuina, t.id_restaurant, t.id_cuina, c.Nom_cuina FROM      tbl_tipus_cuina AS t INNER JOIN tbl_cuina AS c ON t.Id_cuina = c.Id_cuina WHERE t.Id_restaurant = $id");
            $primeraImatge = DB::select("SELECT r.Id_restaurant, r.Nom_restaurant, r.Valoracio, r.Adreca_restaurant, r.Preu_mitja_restaurant, i2.id_imatge, i2.Ruta_Imatge, r.id_restaurant FROM tbl_restaurant r
            LEFT JOIN (SELECT MIN(id_imatge) as id_imatge, id_restaurant FROM `tbl_imatge` GROUP BY Id_restaurant) i ON r.Id_restaurant = i.id_restaurant
            LEFT JOIN tbl_imatge i2 ON i2.Id_imatge = i.id_imatge and i.id_restaurant = i2.id_restaurant WHERE r.Id_restaurant = $id");
            //Devolver esos datos y mostrarlos
            DB::commit();
            return view('dv_modificar', compact('restaurant', 'lista_cuines', 'cocinas_seleccionadas', 'primeraImatge')); 
        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th;
        }
    }

    public function actualizarRestaurante(RestaurantModifyRequest $request){
        try {
            DB::beginTransaction();
            //Recoge datos restaurante
            $datos_restaurante=request()->except('_token', 'continuar', '_method', 'tiposCocinas', 'imatge', 'userId', 'destinatario', 'nom_gerent');
            $id = $datos_restaurante['Id_restaurant'];
            //Recoge datos cocina
            $datos_cocinas=request()->except('_token', 'continuar', '_method', 'Nom_restaurant', 'Adreca_restaurant', 'Preu_mitja_restaurant', 'Correu_gerent_restaurant', 'Descripcio_restaurant');
            //Recoge datos imagen
            $datos_imagen = request()->except('_token', 'continuar', '_method');
            
            //Actualizamos datos del restaurante
            DB::table('tbl_restaurant')->where('Id_restaurant', "=", $id)->update($datos_restaurante);
            
            //Eliminamos el tipo de cocina del restaurante modificado
            DB::table('tbl_tipus_cuina')->where('Id_restaurant', '=', $id)->delete();
            
            //Comprobamos si esta inicializada la llave 'tiposCocinas'
            if(isset($datos_cocinas['tiposCocinas'])){
                $tipos_cocinas = $datos_cocinas['tiposCocinas'];
                foreach($tipos_cocinas as $tipoCocina){
                    $cocinas = DB::table('tbl_cuina')
                    ->where([['Nom_cuina','=',$tipoCocina]])->get();
                    foreach($cocinas as $cocina){
                        echo $cocina->Id_cuina;
                        echo $id;
                        DB::table('tbl_tipus_cuina')->insert(['Id_restaurant'=>$id, 'Id_cuina'=>$cocina->Id_cuina]);
                    }
                }
            }
    
            if ($request->file('imatge')) {
                //Recogemos el restaurante en questión
                $imgBD = DB::table('tbl_imatge')->where('Id_restaurant', $id)->first();
                //Recogemos la imagen y la guardamos en local
                $rutaImatge=$request->file('imatge')->store('uploads','public');
                //Eliminamos la foto en local
                Storage::delete('public/'.$imgBD->Ruta_Text_Imatge);
                //Actualizamos la ruta de la imagen
                DB::table('tbl_imatge')->where('Id_restaurant','=',$id)->update(['Ruta_Text_Imatge'=>$rutaImatge]);
    
                //Metodo antiguo
                $img = $request->file('imatge')->getRealPath();
                $bin = file_get_contents($img);
                DB::table('tbl_imatge')->where('Id_restaurant', '=', $id)->update(['Ruta_imatge'=>$bin]);
            }
    
            //Enviamos el correo
            $co = $datos_imagen['destinatario'];
            $datos_correo = "Estimado Sr/a. ".$datos_imagen['nom_gerent']." Informarle de que su restaurante ".$datos_imagen['Nom_restaurant']. " ha sido modificado Saludos cordiales, Deliveroo";
            $enviar = new EnviarCorreoGerente($datos_correo);
            $enviar->asunto = "Asunto test";
            Mail::to($co)->send($enviar);
    
            DB::commit();
            return redirect('/');

        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th;
        }
    }

    public function verRestaurante($id) {
        $restaurant=DB::table('tbl_restaurant')->WHERE('Id_restaurant','=', $id)->first(); 
        $lista_cuines = DB::table('tbl_cuina')->get();
        $cocinas_seleccionadas = DB::select("SELECT t.id_tipus_cuina, t.id_restaurant, t.id_cuina, c.Nom_cuina FROM  tbl_tipus_cuina AS t INNER JOIN tbl_cuina AS c ON t.Id_cuina = c.Id_cuina WHERE t.Id_restaurant = $id");
        $primeraImatge = DB::select("SELECT r.Id_restaurant, r.Nom_restaurant, r.Valoracio, r.Adreca_restaurant, r.Preu_mitja_restaurant, i2.id_imatge, i2.Ruta_Imatge, r.id_restaurant FROM tbl_restaurant r
        LEFT JOIN (SELECT MIN(id_imatge) as id_imatge, id_restaurant FROM `tbl_imatge` GROUP BY Id_restaurant) i ON r.Id_restaurant = i.id_restaurant
        LEFT JOIN tbl_imatge i2 ON i2.Id_imatge = i.id_imatge and i.id_restaurant = i2.id_restaurant WHERE r.Id_restaurant = $id");
        //Devolver esos datos y mostrarlos
        return view('ver_restaurante', compact('restaurant', 'lista_cuines', 'cocinas_seleccionadas', 'primeraImatge'));
    }
    
    public function filter(Request $request) {
        $nombreRestaurante = $request->input('nombreRestaurante');
        $precioMedio = $request->input('precioMedio');
        $valoracion = $request->input('valoracion');
        $tipoCocina = $request->input('tipoCocina');
        $userId = $request->input('userId');
        if ($userId == '') { // Si no és un usuari estàndard, establim el valor de userId a -1
            $userId = -1;
        }
        $query = 'SELECT r.Id_restaurant, f.Id_favorit, r.Nom_restaurant, r.Valoracio, r.Adreca_restaurant, r.Preu_mitja_restaurant, i2.id_imatge, i2.Ruta_Imatge, r.id_restaurant FROM tbl_restaurant r
        LEFT JOIN (SELECT MIN(id_imatge) as id_imatge, id_restaurant FROM `tbl_imatge` GROUP BY Id_restaurant) i ON r.Id_restaurant = i.id_restaurant
        LEFT JOIN tbl_imatge i2 ON i2.Id_imatge = i.id_imatge and i.id_restaurant = i2.id_restaurant
        LEFT JOIN tbl_favorit f ON r.Id_restaurant = f.Id_restaurant AND f.Id_usuari = ?';
        $queryConditions = '';
        $queryParams = [];
        array_push($queryParams, $userId);
        
        if ($nombreRestaurante != '') {
            $queryConditions .= ' WHERE Nom_restaurant LIKE ? ';
            array_push($queryParams, '%'.$nombreRestaurante.'%');
        }
        if ($precioMedio != '') {
            // $queryConditions .= ' WHERE Preu_mitja_restaurant <= ? ';
            $queryConditions .= ($queryConditions != '' ?' AND ':' WHERE ') . ' Preu_mitja_restaurant <= ? ';
            array_push($queryParams, intval($precioMedio));
        }
        if ($valoracion != '') {
            $queryConditions .= ($queryConditions != '' ?' AND ':' WHERE ') . ' Valoracio >= ? ';
            array_push($queryParams, intval($valoracion));
        }
        if ($tipoCocina != '') {
            $queryConditions .= ($queryConditions != '' ?' AND ':' WHERE ') . ' EXISTS (
                SELECT Id_tipus_cuina 
                FROM tbl_tipus_cuina tc
                INNER JOIN tbl_cuina c 
                ON tc.Id_cuina = c.Id_cuina 
                WHERE tc.Id_restaurant = r.Id_restaurant 
                AND c.Nom_cuina IN (' .$tipoCocina .')
            )';
        }
        $restaurantes = DB::select($query. $queryConditions, $queryParams);
    
        foreach($restaurantes as $restaurante) {
            if($restaurante->Ruta_Imatge!=null) {
                $restaurante->Ruta_Imatge = base64_encode($restaurante->Ruta_Imatge);
            }
        }
        return response()->json($restaurantes, 200);
    }

    public function eliminarRestaurante(Request $request) {
        $id = $request->input('id_restaurante');
        try {
            DB::beginTransaction();
            DB::table('tbl_comentari')->where('Id_restaurant', '=', $id)->delete();
            DB::table('tbl_imatge')->where('Id_restaurant', '=', $id)->delete();
            DB::table('tbl_tipus_cuina')->where('Id_restaurant', '=', $id)->delete();
            DB::table('tbl_valoracio')->where('Id_restaurant', '=', $id)->delete();
            DB::table('tbl_restaurant')->where('Id_restaurant', '=', $id)->delete();
            DB::commit();
            return redirect('dv_admin');
        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th;
        }
    }

    public function getTags(Request $request) {
        $id = intval($request->input('idUsuario'));

        $query = 'SELECT `tbl_tag`.*, `tbl_tag_intermitja`.* FROM `tbl_tag` LEFT JOIN `tbl_tag_intermitja` ON `tbl_tag_intermitja`.`Id_tag` = `tbl_tag`.`Id_tag` WHERE Id_usuari = ' . $id;
        $tags = DB::select($query);
        return response()->json($tags, 200);
    }

    public function getRestaurantTags(Request $request) {
        $idUsuario = intval($request->input('idUsuario'));
        $id_restaurant = intval($request->input('id_restaurant'));
        
        $query = 'SELECT `tbl_tag`.*, `tbl_tag_intermitja`.* FROM `tbl_tag` LEFT JOIN `tbl_tag_intermitja` ON `tbl_tag_intermitja`.`Id_tag` = `tbl_tag`.`Id_tag` 
        WHERE Id_usuari = ' . $idUsuario . ' AND Id_restaurant = ' . $id_restaurant;
        $tags = DB::select($query);
        return response()->json($tags, 200);
    }

    public function addTag(Request $request) {
        //Recogemos todos los datos
        $datos=$request->except('_token');
        $tag = $datos['tag'];
        $id_restaurant = $datos['id_restaurant'];
        $id_usuari = $datos['id_usuari'];

        try {
            //Insertamos el tag en la tbl_tag
            DB::table('tbl_tag')->insertGetId(['Nom_tag'=>$tag]);
            //Recogemos la id del tag insertado
            $id_tag = DB::getPdo()->lastInsertId();
            //Insertamos el registro en la tabla intermedia
            DB::table('tbl_tag_intermitja')->insertGetId(['Id_restaurant'=>$id_restaurant, 'Id_tag'=>$id_tag, 'Id_usuari'=>$id_usuari]);
        } catch (\Throwable $th) {
        }
    }

    public function eliminarTag(Request $request) {
        $id_tag = $request->input('id_tag');
        try {
            DB::table('tbl_tag')->where('Id_Tag', '=', $id_tag)->delete();
            DB::table('tbl_tag_intermitja')->where('Id_Tag', '=', $id_tag)->delete();
        } catch (\Throwable $th) {
        }
    }

    public function getComentarios(Request $request) {
        // $token = $request->input('_token');
        $id = intval($request->input('id_restaurant'));
        $query = 'SELECT c.Id_comentari, c.Id_restaurant, c.Id_usuari, c.Comentari, u.Nom_usuari
        FROM tbl_comentari c INNER JOIN tbl_usuari u ON c.Id_usuari = u.Id_usuari
        WHERE c.Id_restaurant = ? ORDER BY c.Id_Comentari DESC';
        $comentarios = DB::select($query, [$id]);
        return response()->json($comentarios, 200);
    }

    public function addComentario(Request $request) {
        $token = $request->input('_token');
        $id_restaurant = intval($request->input('id_restaurant'));
        $id_usuari = intval($request->input('id_usuari'));
        $comentario = $request->input('comentario');
        try {
            DB::beginTransaction();
            DB::table('tbl_comentari')->insert([
                'Id_restaurant' => $id_restaurant,
                'Id_usuari' => $id_usuari,
                'Comentari' => $comentario,
            ]);
            DB::commit();
            return response()->json(array('resultado'=>'OK'),200);
        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th;
        }
    }

    public function getValoracion(Request $request) {
        $id_restaurant = intval($request->input('id_restaurant'));
        $id_usuari = intval($request->input('id_usuari'));
        $userQuery = DB::table('tbl_valoracio')
            ->where([['Id_restaurant','=',$id_restaurant], ['Id_usuari','=',$id_usuari]])->count(); 
            if ($userQuery > 0) {
                // Entonces el usuario ha valorado el restaurante actual
                // Nos traemos la valoración 
                $queryValoracion = DB::table("tbl_valoracio")->where("Id_restaurant", $id_restaurant)->where("Id_usuari", $id_usuari)->first();
                $valoracion = $queryValoracion->Valoracio;
                return response()->json($valoracion, 200);
        }  
    }

    public function puntuar(Request $request) {
        $token = $request->input('_token');
        $id_restaurant = intval($request->input('id_restaurant'));
        $id_usuari = intval($request->input('id_usuari'));
        $puntuacion = $request->input('puntuacion');
        try {
            DB::beginTransaction();
            $userQuery = DB::table('tbl_valoracio')
            ->where([['Id_restaurant','=',$id_restaurant], ['Id_usuari','=',$id_usuari]])->count();
            if ($userQuery == 1) {
                // Este usuario ha valorado anteriormente este restaurante, por lo que actualizamos la puntuación:
                DB::table('tbl_valoracio')->where([['Id_restaurant', '=', $id_restaurant], ['Id_usuari', '=', $id_usuari]])->update(['Valoracio'=>$puntuacion]);
            } else {
                // El usuario puntúa por primera vez el restaurante
                DB::table('tbl_valoracio')->insert([
                    'Id_restaurant' => $id_restaurant,
                    'Id_usuari' => $id_usuari,
                    'Valoracio' => $puntuacion,
                ]);
            }
            $queryPuntuacion = DB::select("SELECT SUM(Valoracio) AS suma FROM tbl_valoracio WHERE Id_restaurant = $id_restaurant");
            $totalPuntuacion = $queryPuntuacion[0]->suma;
            $countValoracio = DB::table('tbl_valoracio')->where([['Id_restaurant','=',$id_restaurant]])->count();
            $puntuacionMedia = $totalPuntuacion / $countValoracio;
            print_r('Puntuacion media:', $puntuacionMedia);
            print_r('countValoracio:', $countValoracio);
            print_r('countValoracio:', $countValoracio);
            DB::table('tbl_restaurant')->WHERE('Id_restaurant', '=', $id_restaurant)->update(['Valoracio'=>$puntuacionMedia]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th;
        }
    }
    // FIN COMENTARIOS

    // REVIEW
    public function favorito(Request $request) {
        $id_restaurant = intval($request->input('id_restaurante'));
        $id_usuari = intval($request->input('id_usuari'));
        try {
            DB::beginTransaction();
            // Primer comprobem si aquest usuari ja te com a favorit aquest restaurant
            $existFavorite = DB::table('tbl_favorit')->where([['Id_usuari','=',$id_usuari],['Id_restaurant','=',$id_restaurant]])->count();

            if ($existFavorite == '0') { // No el té com a favorit, llavors insertem registre
                DB::table('tbl_favorit')->insertGetId(['Id_usuari'=>$id_usuari,'Id_restaurant'=>$id_restaurant]);
            } else {
                // El té com a favorit, llavors eliminem el registre
                DB::table('tbl_favorit')->where(['Id_usuari'=>$id_usuari,'Id_restaurant'=>$id_restaurant])->delete();
            }
            // die;
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            echo "No va bien $th";
        }
    }
    // END REVIEW
}
