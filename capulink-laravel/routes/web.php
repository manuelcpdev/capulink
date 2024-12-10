<?php

use App\Http\Controllers\ConexionController;
use App\Http\Controllers\RexistroController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\LigazonController;
use App\Http\Controllers\PerfilController;
use App\Models\Grupo;

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

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/desconexion', [AutenticacionController::class, 'desconectar']);
    Route::post('/desconexion', [AutenticacionController::class, 'desconectar']);
    Route::middleware('comprobarRol')->group( function () {
        Route::get('/admin');
    });
    Route::get('/usuario-estado', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'conectado' => $user ? true : false,
            'eAdmin' => $user ? $user->admin : false,
        ]);
    });
    Route::middleware('auth:sanctum')->get('/usuario-conectado', function (Request $request) {
        return response()->json(['conectado' => true]);
    });
});

Route::resource('administracion/ligazon/', PerfilController::class);

Route::get('/usuarios/:id/ligazons', [LigazonController::class, 'obterLigazonsUsuario']);
Route::get('/usuarios/:id/perfil');
Route::get('/grupos/:id');
Route::get('/grupos'); //

Route::get('/perfil/{name}', [PerfilController::class, 'show']);
Route::get('/perfil', [PerfilController::class, 'amosarConectado']);

Route::get('/csrf', function(Request $request){
    return view('csrf');
});

Route::post('/usuarios/ligazons', [LigazonController::class, 'crearLigazonDeUsuario']);
Route::get('/usuarios/ligazons', [LigazonController::class, 'obterLigazonsUsuarioConectado']);
Route::get('/usuarios/ligazons/{name}', [LigazonController::class, 'obterLigazonsUsuario']);

Route::post('/categorias', [CategoriaController::class, 'store']);

Route::post('/grupo/crear', [GrupoController::class, 'store']);
Route::post('/grupo/modificar', [GrupoController::class, 'updateGrupo']); // admin deberia poder
Route::post('/grupo/eliminar', [GrupoController::class, 'deleteGrupo']); // admin deberia poder
Route::post('/grupo/unirse', [GrupoController::class, 'joinGrupo']);
Route::post('/grupo/sair', [GrupoController::class, 'forfeitGrupo']);

//Por probar
Route::get('/grupos/usuario/miembro', [GrupoController::class, 'getGruposWithMembership']);
Route::get('/grupos/usuario/creador', [GrupoController::class, 'getGruposOfCreator']);
Route::get('/grupos/usuario', [GrupoController::class, 'getGruposUsuario']); //todos los grupos del usuario
Route::get('/grupos/publicos', [GrupoController::class, 'getGruposPublicos']);
Route::get('/grupo/{id}', [GrupoController::class, 'getGrupo']);

// Ligazons de grupo
// Etiquetas de grupo
// Etiquetas de las ligazons del grupo
