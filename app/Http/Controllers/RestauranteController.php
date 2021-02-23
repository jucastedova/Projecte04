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

            $tipos_categorias = $datos['tiposCategoria'];
    
            foreach($tipos_categorias as $tipoCat){
                $categorias = DB::table('tbl_categoria')
                ->where([['Nom_categoria','=',$tipoCat]])->get();
                foreach($categorias as $cat){
                    DB::table('tbl_tipus_categoria')->insertGetId(['Id_restaurant'=>$data, 'Id_categoria'=>$cat->Id_categoria]);
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
            $lista_categories = DB::table('tbl_categoria')->get();
            $cocinas_seleccionadas = DB::select("SELECT t.id_tipus_cuina, t.id_restaurant, t.id_cuina, c.Nom_cuina FROM tbl_tipus_cuina AS t INNER JOIN tbl_cuina AS c ON t.Id_cuina = c.Id_cuina WHERE t.Id_restaurant = $id");
            $categorias_seleccionadas = DB::select("SELECT tc.*, c.* FROM tbl_tipus_categoria AS tc INNER JOIN tbl_categoria AS c ON tc.Id_categoria = c.Id_categoria WHERE tc.Id_restaurant = $id");
            $primeraImatge = DB::select("SELECT r.Id_restaurant, r.Nom_restaurant, r.Valoracio, r.Adreca_restaurant, r.Preu_mitja_restaurant, i2.id_imatge, i2.Ruta_Imatge, r.id_restaurant FROM tbl_restaurant r
            LEFT JOIN (SELECT MIN(id_imatge) as id_imatge, id_restaurant FROM `tbl_imatge` GROUP BY Id_restaurant) i ON r.Id_restaurant = i.id_restaurant
            LEFT JOIN tbl_imatge i2 ON i2.Id_imatge = i.id_imatge and i.id_restaurant = i2.id_restaurant WHERE r.Id_restaurant = $id");
            //Devolver esos datos y mostrarlos
            DB::commit();
            return view('dv_modificar', compact('restaurant', 'lista_cuines', 'cocinas_seleccionadas', 'primeraImatge', 'categorias_seleccionadas', 'lista_categories')); 
        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th;
        }
    }

    public function actualizarRestaurante(RestaurantModifyRequest $request){
        try {
            DB::beginTransaction();
            //Recoge datos restaurante
            $datos_restaurante=request()->except('_token', 'continuar', '_method', 'tiposCocinas', 'tiposCategorias', 'imatge', 'userId', 'destinatario', 'nom_gerent');
            $id = $datos_restaurante['Id_restaurant'];
            //Recoge datos cocina
            $datos_cocinas=request()->except('_token', 'continuar', '_method', 'Nom_restaurant', 'Adreca_restaurant', 'Preu_mitja_restaurant', 'Correu_gerent_restaurant', 'Descripcio_restaurant');
            //Recoge datos categorias
            $datos_categorias=request()->except('_token', 'continuar', '_method', 'Nom_restaurant', 'Adreca_restaurant', 'Preu_mitja_restaurant', 'Correu_gerent_restaurant', 'Descripcio_restaurant', 'tiposCocinas', 'imatge');
            
            //Recoge datos imagen
            $datos_imagen = request()->except('_token', 'continuar', '_method');
            
            //Actualizamos datos del restaurante
            DB::table('tbl_restaurant')->where('Id_restaurant', "=", $id)->update($datos_restaurante);
            
            //Eliminamos el tipo de cocina del restaurante modificado
            DB::table('tbl_tipus_cuina')->where('Id_restaurant', '=', $id)->delete();
            //Eliminamos el tipo de categorias del restaurante modificado
            DB::table('tbl_tipus_categoria')->where('Id_restaurant', '=', $id)->delete();
            
            //Comprobamos si esta inicializada la llave 'tiposCocinas'
            if(isset($datos_cocinas['tiposCocinas'])){
                $tipos_cocinas = $datos_cocinas['tiposCocinas'];
                foreach($tipos_cocinas as $tipoCocina){
                    $cocinas = DB::table('tbl_cuina')
                    ->where([['Nom_cuina','=',$tipoCocina]])->get();
                    foreach($cocinas as $cocina){
                        DB::table('tbl_tipus_cuina')->insert(['Id_restaurant'=>$id, 'Id_cuina'=>$cocina->Id_cuina]);
                    }
                }
            }

            //Comprobamos si esta inicializada la llave 'tiposCategorias'
            if(isset($datos_categorias['tiposCategorias'])){
                $tipos_categorias = $datos_categorias['tiposCategorias'];
                foreach($tipos_categorias as $tipoCategoria){
                    $categorias = DB::table('tbl_categoria')
                    ->where([['Nom_categoria','=',$tipoCategoria]])->get();
                    foreach($categorias as $cat){
                        DB::table('tbl_tipus_categoria')->insert(['Id_restaurant'=>$id, 'Id_categoria'=>$cat->Id_categoria]);
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

    public function buscarPorTag($filtro) {
        $c = substr($filtro, 0, 1);
        if($c == "#") {
            return true;
        }
        return false;
    }
    
    public function filter(Request $request) {
        $nombreRestaurante = $request->input('nombreRestaurante');
        $precioMedio = $request->input('precioMedio');
        $valoracion = $request->input('valoracion');
        $tipoCocina = $request->input('tipoCocina');
        $tipoCat = $request->input('tipoCat');
        $userId = $request->input('userId');
        $flagTag = $this->buscarPorTag($nombreRestaurante);
        if ($userId == '') { // Si no és un usuari estàndard, establim el valor de userId a -1
            $userId = -1;
        }

        $query = 'SELECT r.Id_restaurant, f.Id_favorit, r.Nom_restaurant, r.Valoracio, r.Adreca_restaurant, r.Preu_mitja_restaurant, i2.id_imatge, i2.Ruta_Imatge, r.id_restaurant FROM tbl_restaurant r
        LEFT JOIN tbl_imatge i2 ON i2.Id_restaurant = r.Id_restaurant
        LEFT JOIN tbl_favorit f ON f.Id_usuari = ? AND r.Id_restaurant = f.Id_restaurant';

        $queryConditions = '';
        $queryParams = [];
        array_push($queryParams, $userId);
        if ($request->input('favorito')) {
            $queryConditions .= ($queryConditions != '' ?' AND ':' WHERE ') . ' f.Id_favorit IS NOT null';
        }
        if($flagTag) {
            if ($nombreRestaurante != '') {
                $tag = substr($nombreRestaurante, 1);
                $queryConditions .= ($queryConditions != '' ?' AND ':' WHERE ') .' EXISTS (SELECT inter.Id_restaurant FROM tbl_tag_intermitja inter INNER JOIN tbl_tag t ON inter.Id_tag = t.Id_tag WHERE inter.Id_restaurant = r.Id_restaurant AND Nom_tag IN (\''.$tag.'\'))';
            }
        } else {
            if ($nombreRestaurante != '') {
                $queryConditions .= ($queryConditions != '' ?' AND ':' WHERE ') .' Nom_restaurant LIKE ? ';
                array_push($queryParams, '%'.$nombreRestaurante.'%');
            }
        }
        
        if ($precioMedio != '') {
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
        if ($tipoCat != '') {
            $queryConditions .= ($queryConditions != '' ?' AND ':' WHERE ') . ' EXISTS (
                SELECT Id_tipus_categoria 
                FROM tbl_tipus_categoria tcat
                INNER JOIN tbl_categoria cat 
                ON tcat.Id_categoria = cat.Id_categoria 
                WHERE tcat.Id_restaurant = r.Id_restaurant 
                AND cat.Nom_categoria IN (' .$tipoCat .')
            )';
        }
        
        $restaurantes = DB::select($query. $queryConditions, $queryParams);
    
        foreach($restaurantes as $restaurante) {
            if($restaurante->Ruta_Imatge!=null) {
                $restaurante->Ruta_Imatge = base64_encode($restaurante->Ruta_Imatge);
            }
        }
        // $restaurantes[0]->query = $query;
        // if (count($restaurantes) == 0) {
        //     print_r($query);
        // }
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
            DB::table('tbl_tipus_categoria')->where('Id_restaurant', '=', $id)->delete();
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

        $query = 'SELECT `tbl_tag`.*, `tbl_tag_intermitja`.*, `tbl_restaurant`.* FROM `tbl_tag` LEFT JOIN `tbl_tag_intermitja` ON `tbl_tag_intermitja`.`Id_tag` = `tbl_tag`.`Id_tag` LEFT JOIN `tbl_restaurant` ON `tbl_tag_intermitja`.`Id_restaurant` = `tbl_restaurant`.`Id_restaurant` WHERE Id_usuari = ' . $id;
        $tags = DB::select($query);
        return response()->json($tags, 200);
    }

    public function getRestaurantTags(Request $request) {
        $idUsuario = intval($request->input('idUsuario'));
        $id_restaurant = intval($request->input('id_restaurant'));
        // REVIEW
        // Mirem si hi ha tags en aquest restaurant
        // $countTags = DB::table('tbl_tag_intermitja')
            // ->where([['Id_restaurant','=',$id_restaurant], ['Id_usuari','=',$idUsuario]])->count();
        // if ($countTags > 0) {
            // Hi ha tags 
            $query = 'SELECT `tbl_tag`.*, `tbl_tag_intermitja`.* FROM `tbl_tag` LEFT JOIN `tbl_tag_intermitja` ON `tbl_tag_intermitja`.`Id_tag` = `tbl_tag`.`Id_tag` 
            WHERE Id_usuari = ' . $idUsuario . ' AND Id_restaurant = ' . $id_restaurant;
            $tags = DB::select($query);
            return response()->json($tags, 200);
        // } 
        // END REVIEW
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

    public function getCategorias() {        
        $query = 'SELECT `tbl_categoria`.* FROM `tbl_categoria`';
        $categorias = DB::select($query);
        return response()->json($categorias, 200);
    }

    public function eliminarCategoria(Request $request) {
        $id_cat = $request->input('id_cat');
        try {
            DB::table('tbl_categoria')->where('Id_categoria', '=', $id_cat)->delete();
            DB::table('tbl_tipus_categoria')->where('Id_categoria', '=', $id_cat)->delete();
            return response()->json("OK");
        } catch (\Throwable $th) {
            return response()->json("KO");
        }
    }

    public function addCategoria(Request $request) {
        //Recogemos todos los datos
        $datos=$request->except('_token');
        $cat = $datos['cat'];

        try {
            //Insertamos el tag en la tbl_tag
            DB::table('tbl_categoria')->insertGetId(['Nom_categoria'=>$cat]);
            return response()->json("OK");
        } catch (\Throwable $th) {
            return response()->json("KO");
        }
    }

    public function updateCategoria(Request $request) {
        //Recogemos todos los datos
        $datos=$request->except('_token');
        $nombre = $datos['Nombre_categoria'];
        $id = $datos['Id_categoria'];

        try {
            DB::table('tbl_categoria')->where('Id_categoria', "=", $id)->update(['Nom_categoria'=>$nombre]);
            return response()->json("OK");
        } catch (\Throwable $th) {
            return response()->json("KO");
        }
    }

    public function getComentarios(Request $request) {
        $id = intval($request->input('id_restaurant'));
        $countComents = DB::table('tbl_comentari')
            ->where([['Id_restaurant','=',$id]])->count(); 
            // Mirem si hi ha comentaris
        if ($countComents > 0) {
            $query = 'SELECT c.Id_comentari, c.Id_restaurant, c.Id_usuari, c.Comentari, u.Nom_usuari
            FROM tbl_comentari c INNER JOIN tbl_usuari u ON c.Id_usuari = u.Id_usuari
            WHERE c.Id_restaurant = ? ORDER BY c.Id_Comentari DESC';
            $comentarios = DB::select($query, [$id]);
            return response()->json($comentarios, 200);
        } else {
            // No hi ha comentaris
            return response()->json('0', 200);
        }
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

}
