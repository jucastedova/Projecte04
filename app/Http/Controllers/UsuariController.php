<?php

namespace App\Http\Controllers;
use App\Http\Requests\UsuariSignUpRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class UsuariController extends Controller {
    public function viewDv_tags() { // Mètode que redirigeix a la vista tags
        return view('dv_tags');
    }

    public function viewLogin() { // Mètode que redirigeix a la vista login
        return view('login');
    }
    public function viewDv_admin() { 
        if(!session()->has('admin')) {
            return redirect('login');
        }
        // Consulta per obtenir tipus de cuina
        $listCuina = DB::table('tbl_cuina')->get();
        // Consulta per obtenir les categories 
        $listCategories = DB::table('tbl_categoria')->get();
        return view('dv_admin', compact('listCuina'), compact('listCategories'));
    }

    public function viewDv_home() { 
        if(session()->has('admin')) {
            $listCuina = DB::table('tbl_cuina')->get();
            $listCategories = DB::table('tbl_categoria')->get();
            return view('dv_admin', compact('listCuina'), compact('listCategories'));
        }
        // Consulta per obtenir tipus de cuina
        $listCuina = DB::table('tbl_cuina')->get();
        // Consulta per obtenir les categories 
        $listCategories = DB::table('tbl_categoria')->get();
        return view('dv_home', compact('listCuina'), compact('listCategories'));
    }

    public function signupView() { 
        return view('signup');
    }

    public function signupAdminView() { 
        if(!session()->has('admin')) {
            return redirect('login');
        }
        return view('dv_signup_admin');
    }

    public function gestionarTagsAdmin() { 
        if(!session()->has('admin')) {
            return redirect('login');
        }
        return view('dv_gestionar_tags');
    }

    public function registerRestaurantView() { 
        $listCuina = DB::table('tbl_cuina')->get();
        $listCategories = DB::table('tbl_categoria')->get();
        if(!session()->has('admin')) {
            return redirect('login');
        }
        return view('dv_register_restaurant', compact('listCuina'), compact('listCategories'));
    }


    public function loginUser(LoginRequest $request) { // Mètode que controla l'inici de sessió
        $data = request()->except('_token', 'continuar');
        $userQuery = DB::table('tbl_usuari')
            ->where([['Correu_usuari','=',$data['email']], ['Pwd_usuari','=',$data['pwd']]])->count(); // Retorna 1 si troba l'usuari, 0 si no el troba
        if ($userQuery == 1) {
            // Entra si troba usuari
            // Mirem el rol
            $rolQuery = DB::table('tbl_usuari')
                ->select(['Id_rol','Nom_usuari','Id_usuari'])
                ->where('Correu_usuari','=',$data['email'])
                ->get();
            $rol = $rolQuery[0]->Id_rol; // Guardem en aquesta variable el valor del rol obtingut en la query
            $userName = $rolQuery[0]->Nom_usuari;
            $userId = $rolQuery[0]->Id_usuari;
            if($rol == 1) {
                // Entra si el rol és admin
                // Crear una sessió a partir del correu de l'usuari
                $request->session()->put(['admin'=>$data['email']]);
                $request->session()->put(['userName'=>$userName]);
                $request->session()->put(['userId'=>$userId]);
                // Es genera un _token diferent per a la ID de la sessió  per a augmentar la seguretat
                $request->session()->regenerate();
                return redirect('dv_admin');
            } else if ($rol == 2){
                // Entra si el rol es usuari estàndard
                $request->session()->put(['estandard'=>$data['email']]);
                $request->session()->put(['userName'=>$userName]);
                $request->session()->put(['userId'=>$userId]);
                $request->session()->regenerate();
                return redirect('dv_home');
            }
        } else {
            //Si el resultat  del count() és 0 ->  torna al login
            return redirect('login')->with('error', 'noLogin');
        }
    }

    public function signup(UsuariSignUpRequest $request) { // Registre d'un usuari
        $data = $request->except('_token');
        try {
            DB::beginTransaction();
            DB::table('tbl_usuari')->insertGetId([
                'Pwd_usuari' => $data['password'],
                'Nom_usuari'=>$data['nombre'], 
                'Cognom_usuari'=>$data['apellido'],
                'Id_rol' => 2, 
                 'Correu_usuari'=>$data['email']]);
            
            // $userQuery = DB::table('tbl_usuari')
            //      ->select(['Id_usuari'])
            //      ->where('Correu_usuari','=',$data['email'])
            //      ->get();
            $userId = DB::getPdo()->lastInsertId();
            // $userId = $userQuery[0]->Id_usuari;
            $request->session()->put(['estandard'=>$data['email']]);
            $request->session()->put(['userName'=>$data['nombre']]);
            $request->session()->put(['userId'=>$userId]);
            $request->session()->regenerate();
            DB::commit();
            return redirect('dv_home');
        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th;
        }
    }

    public function signupAdmin(UsuariSignUpRequest $request) { // Registre d'un administrador
        $data = $request->except('_token');
        try {
            DB::beginTransaction();
            DB::table('tbl_usuari')->insertGetId([
                'Pwd_usuari' => $data['password'],
                'Nom_usuari'=>$data['nombre'], 
                'Cognom_usuari'=>$data['apellido'],
                'Id_rol' => 1, 
                 'Correu_usuari'=>$data['email']]);
            DB::commit();
            return redirect('dv_admin');
        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th;
        }
    }

    public function cerrarSesion(Request $request) {
        $request->session()->flush();  
        return redirect('/');
    }

    public function modificarView(){
        if(!session()->has('admin')) {
            return redirect('login');
        }
        return view('dv_modificar');
    }

    
}
