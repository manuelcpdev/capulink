<?php

use App\Http\Controllers\ConexionController;
use App\Http\Controllers\RexistroController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LigazonController;
use App\Http\Controllers\PerfilController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::post('rexistro', [AutenticacionController::class, 'rexistrar']);
Route::post('conexion', [AutenticacionController::class, 'conectar']);
Route::post('desconexion', [AutenticacionController::class, 'desconectar']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function (Request $request) {
    return response()->json('test');
});

Route::resource('administracion/ligazon/', PerfilController::class);

Route::get('/usuarios/:id/ligazons');
Route::get('/usuarios/:id/perfil');
Route::get('/grupos/:id');
Route::get('/grupos');

Route::get('/csrf', function(Request $request){
    return view('csrf');
});

Route::post('/usuarios/ligazons', [LigazonController::class, 'crearLigazonDeUsuario']);

Route::post('/categorias', [CategoriaController::class, 'store']);
