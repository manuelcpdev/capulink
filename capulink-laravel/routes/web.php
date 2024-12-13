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
use App\Models\Ligazon;
use App\Http\Middleware\PodeEditarLigazonUsuario;

/**
 * Páxina principal de Laravel
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Tenta crear unha cookie XSRF-TOKEN (ten que ser devolta no header coma X-XSRF-COOKIE)
 */
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

/**
 * Rutas de autenticación
 */
Route::post('rexistro', [AutenticacionController::class, 'rexistrar']);
Route::post('conexion', [AutenticacionController::class, 'conectar']);
Route::post('desconexion', [AutenticacionController::class, 'desconectar']);

/**
 * Comproba se hai un usuario autenticado nesta sesión
 */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function (Request $request) {
    return response()->json('test');
});

/**
 * Rutas para usuarios autenticados
 */
Route::middleware('auth:sanctum')->group( function () {
    Route::get('/desconexion', [AutenticacionController::class, 'desconectar']);
    Route::post('/desconexion', [AutenticacionController::class, 'desconectar']);
    Route::middleware('comprobarRol')->group( function () {
        Route::get('/admin');
    });

    /**
     * Comproba se o usuario está conectado e se é admin. Devolve o resultado.
     */
    Route::get('/usuario-estado', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'conectado' => $user ? true : false,
            'eAdmin' => $user ? $user->admin : false,
        ]);
    });

    /**
     * Comproba se o usuario está conectado.
     */
    Route::middleware('auth:sanctum')->get('/usuario-conectado', function (Request $request) {
        return response()->json(['conectado' => true]);
    });
    Route::get('/usuario/ligazon/{id}', [LigazonController::class, 'obterLigazonUsuario'])->middleware(PodeEditarLigazonUsuario::class);
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

Route::post('/usuarios/ligazons', [LigazonController::class, 'crearLigazonUsuario']);
Route::post('/usuarios/ligazons/eliminar', [LigazonController::class, 'eliminarLigazonsUsuario']);
Route::post('/usuarios/ligazon', [LigazonController::class, 'actualizarLigazonUsuario']);
Route::get('/usuarios/ligazons', [LigazonController::class, 'obterLigazonsUsuarioConectado']);
Route::get('/usuarios/ligazons/{name}', [LigazonController::class, 'obterLigazonsUsuario']);

Route::post('/categorias', [CategoriaController::class, 'crearCategoria']);

Route::post('/grupo/crear', [GrupoController::class, 'crearGrupo']);
Route::post('/grupo/modificar', [GrupoController::class, 'actualizarGrupo']); // admin deberia poder
Route::post('/grupo/eliminar', [GrupoController::class, 'eliminarGrupo']); // admin deberia poder
Route::post('/grupo/unirse', [GrupoController::class, 'unirGrupo']);
Route::post('/grupo/sair', [GrupoController::class, 'abandoarGrupo']);

//Por probar
Route::get('/grupos/usuario/miembro', [GrupoController::class, 'obterGruposUsuarioMembresia']);
Route::get('/grupos/usuario/creador', [GrupoController::class, 'obterGruposUsuarioCreadorConectado']);
Route::get('/grupos/usuario', [GrupoController::class, 'obterGruposUsuario']); //todos los grupos del usuario
Route::get('/grupos/publicos', [GrupoController::class, 'obterGruposPublicos']);
Route::get('/grupo/{id}', [GrupoController::class, 'obterGrupo']);

// Ligazons de grupo
// Etiquetas de grupo
// Etiquetas de las ligazons del grupo
