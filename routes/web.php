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

// Route::GET('/', [RestauranteController::class, 'index']);
Route::get('read', [RestauranteController::class, 'read']);


// REVIEW
Route::post('searchName', [RestauranteController::class, 'searchName']);
Route::post('filter', [RestauranteController::class, 'filter']);

// END REVIEW

// Route::get('/', [UsuariController::class, 'index']);
Route::get('login', [UsuariController::class, 'viewLogin']);

Route::get('cerrarSesion', [UsuariController::class, 'cerrarSesion']); 
Route::get('signupView', [UsuariController::class, 'signupView']); 
Route::get('signupAdminView', [UsuariController::class, 'signupAdminView']); 

Route::post('loginUser', [UsuariController::class, 'loginUser']);
Route::get('dv_admin', [UsuariController::class, 'viewDv_admin']);
Route::get('dv_home', [UsuariController::class, 'viewDv_home']);
Route::get('/', [UsuariController::class, 'viewDv_home']);
Route::post('signup', [UsuariController::class, 'signup']);
Route::post('signupAdmin', [UsuariController::class, 'signupAdmin']);

Route::post('crearRestaurante', [RestauranteController::class, 'crearRestaurante']);

Route::get('registerRestaurantView', [UsuariController::class, 'registerRestaurantView']);
// Route::get('eliminarRestaurante/{id}', [RestauranteController::class, 'eliminarRestaurante']);
Route::post('eliminarRestaurante', [RestauranteController::class, 'eliminarRestaurante']);
Route::get('modificarRestauranteDatos/{id}', [RestauranteController::class, 'modificarRestauranteDatos']);
Route::put('actualizarRestaurante', [RestauranteController::class, 'actualizarRestaurante']);
Route::get('verRestaurante/{id}', [RestauranteController::class, 'verRestaurante']);

Route::post('getComentarios', [RestauranteController::class, 'getComentarios']);
Route::post('addComentario', [RestauranteController::class, 'addComentario']);
Route::post('puntuar', [RestauranteController::class, 'puntuar']);

Route::post('getValoracion', [RestauranteController::class, 'getValoracion']);

// REVIEW
Route::post('favorito', [RestauranteController::class, 'favorito']);
// END REVIEW
