<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariController;
use App\Http\Controllers\RestauranteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/////////////////////////////////////////////
/////////////RestauranteController///////////
/////////////////////////////////////////////
Route::get('read', [RestauranteController::class, 'read']);
Route::post('searchName', [RestauranteController::class, 'searchName']);
Route::post('filter', [RestauranteController::class, 'filter']);
Route::post('crearRestaurante', [RestauranteController::class, 'crearRestaurante']);
Route::post('eliminarRestaurante', [RestauranteController::class, 'eliminarRestaurante']);
Route::get('modificarRestauranteDatos/{id}', [RestauranteController::class, 'modificarRestauranteDatos']);
Route::put('actualizarRestaurante', [RestauranteController::class, 'actualizarRestaurante']);
Route::get('verRestaurante/{id}', [RestauranteController::class, 'verRestaurante']);
Route::post('getComentarios', [RestauranteController::class, 'getComentarios']);
Route::post('addComentario', [RestauranteController::class, 'addComentario']);
Route::post('puntuar', [RestauranteController::class, 'puntuar']);
Route::post('getValoracion', [RestauranteController::class, 'getValoracion']);
Route::post('favorito', [RestauranteController::class, 'favorito']);
Route::post('getTags', [RestauranteController::class, 'getTags']);
Route::post('eliminarTag', [RestauranteController::class, 'eliminarTag']);
Route::post('addTag', [RestauranteController::class, 'addTag']);
Route::post('getRestaurantTags', [RestauranteController::class, 'getRestaurantTags']);

/////////////////////////////////////////////
/////////////////UsuariController////////////
/////////////////////////////////////////////
Route::get('/', [UsuariController::class, 'viewDv_home']);
Route::get('login', [UsuariController::class, 'viewLogin']);
Route::post('loginUser', [UsuariController::class, 'loginUser']);
Route::get('signupView', [UsuariController::class, 'signupView']); 
Route::post('signupAdmin', [UsuariController::class, 'signupAdmin']);
Route::get('signupAdminView', [UsuariController::class, 'signupAdminView']); 
Route::post('signup', [UsuariController::class, 'signup']);
Route::get('registerRestaurantView', [UsuariController::class, 'registerRestaurantView']);
Route::get('cerrarSesion', [UsuariController::class, 'cerrarSesion']); 
Route::get('dv_admin', [UsuariController::class, 'viewDv_admin']);
Route::get('dv_home', [UsuariController::class, 'viewDv_home']);
Route::get('dv_tags', [UsuariController::class, 'viewDv_tags']);
