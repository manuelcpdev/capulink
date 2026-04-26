<?php

use App\Http\Controllers\ConexionController;
use App\Http\Controllers\RexistroController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\LigazonController;
use App\Http\Controllers\LigazonUsuarioController;
use App\Http\Controllers\PerfilController;
use App\Models\Grupo;
use App\Models\Ligazon;
use App\Http\Middleware\PodeEditarLigazonUsuario;
use App\Http\Middleware\comprobarRol;
use App\Http\Middleware\ComprobarConexion;
use App\Http\Resources\LigazonResource;
use App\Http\Resources\LigazonUsuarioResource;
use App\Http\Resources\UserResource;
use App\Models\LigazonUsuario;
use App\Models\User;
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
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/
Route::get('/user', function (Request $request) {
    return 'test';
})->middleware([ComprobarConexion::class]);
Route::get('/test', function (Request $request) {
    return response()->json('test');
});

/**
 * Rutas para usuarios autenticados
 */
Route::middleware('auth:sanctum')->group( function () {
    Route::get('/desconexion', [AutenticacionController::class, 'desconectar']);
    Route::post('/desconexion', [AutenticacionController::class, 'desconectar']);
    Route::middleware(comprobarRol::class)->group( function () {
        Route::get('/admin', function(Request $request) {
            return response()->json([
                'eAdmin' => true,
            ]);
        });
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

    //Route::get('/usuario/ligazon/{id}', [LigazonController::class, 'obterLigazonUsuario'])->middleware(PodeEditarLigazonUsuario::class);
    Route::get('/usuario/ligazon/{ligazonUsuario}', [LigazonUsuarioController::class, 'show']);
});

Route::resource('administracion/ligazon/', PerfilController::class);


Route::get('/usuarios/:id/ligazons', [LigazonController::class, 'obterLigazonsUsuario']);
Route::get('/usuarios/:id/perfil');
Route::get('/grupos/:id');
Route::get('/grupos'); //

Route::get('/perfil/{name}', [PerfilController::class, 'show']);
Route::get('/perfil', [PerfilController::class, 'amosarConectado'])->middleware('auth:sanctum');

Route::get('/csrf', function(Request $request){
    return view('csrf');
});

//Route::post('/usuarios/ligazons', [LigazonController::class, 'crearLigazonUsuario']);
//Route::post('/usuarios/ligazons/eliminar', [LigazonController::class, 'eliminarLigazonsUsuario']);
//Route::post('/usuarios/ligazon', [LigazonController::class, 'actualizarLigazonUsuario']);
//Route::get('/usuarios/ligazons', [LigazonController::class, 'obterLigazonsUsuarioConectado']);

Route::post('/usuarios/ligazons', [LigazonUsuarioController::class, 'store']);
Route::post('/usuarios/ligazons/eliminar', [LigazonUsuarioController::class, 'eliminarLigazons']);

//TODO: Corrixir update
Route::post('/usuarios/ligazon/{ligazonUsuario}', [LigazonUsuarioController::class, 'update']);
Route::get('/usuarios/ligazons', [LigazonUsuarioController::class, 'indexConnectedUser']);
Route::get('/usuario/{user}/ligazons', [LigazonUsuarioController::class, 'indexByUserID']);
Route::put('/usuario/ligazon/{ligazonUsuario}', [LigazonUsuarioController::class, 'update']);

Route::get('/usuarios/ligazons/{name}', [LigazonController::class, 'obterLigazonsUsuario']);

Route::post('/categorias', [CategoriaController::class, 'crearCategoria']);

Route::post('/grupo/crear', [GrupoController::class, 'crearGrupo']);
Route::post('/grupo/modificar', [GrupoController::class, 'actualizarGrupo']); // admin deberia poder
Route::post('/grupo/eliminar', [GrupoController::class, 'eliminarGrupo']); // admin deberia poder
Route::post('/grupo/unirse', [GrupoController::class, 'unirGrupo']);
Route::post('/grupo/sair', [GrupoController::class, 'abandoarGrupo']);

//
Route::get('/grupos/usuario/miembro', [GrupoController::class, 'obterGruposUsuarioMembresia']);
Route::get('/grupos/usuario/creador', [GrupoController::class, 'obterGruposUsuarioCreadorConectado']);
Route::get('/grupos/usuario', [GrupoController::class, 'obterGruposUsuario']); //todos los grupos del usuario
Route::get('/grupos/publicos', [GrupoController::class, 'obterGruposPublicos']);
Route::get('/grupo/{id}', [GrupoController::class, 'obterGrupo']);

Route::post('/ligazons/grupo/crear', [LigazonController::class, 'crearLigazonDeGrupo']);
Route::post('/ligazons/grupo/eliminar', [LigazonController::class, 'eliminarLigazonsDeGrupo']);
Route::post('/ligazons/grupo/modificar', [LigazonController::class, 'actualizarLigazonDeGrupo']);

//
Route::post('/ligazons/usuario/modificar', [LigazonController::class, 'updateLigazonDeUsuario']);
Route::post('/ligazons/usuario/eliminar', [LigazonController::class, 'deleteLigazonsDeUsuario']);
Route::get('/ligazons/grupo/{id}', [LigazonController::class, 'obterLigazonsPorGrupo']);

Route::get('/usuarios', function() {
    return UserResource::collection(User::paginate());
})->middleware(comprobarRol::class);

Route::get('/ligazons', function() {
    return LigazonResource::collection(Ligazon::paginate());
})->middleware(comprobarRol::class);

Route::get('/ligazons/usuarios', function() {
    return LigazonUsuarioResource::collection(LigazonUsuario::paginate());
})->middleware(comprobarRol::class);

Route::get('/ligazon/usuario/{id}', [LigazonController::class, 'obterLigazonUsuario2']);
Route::get('/ligazons/usuario', [LigazonController::class, 'obterLigazonsUsuario2']);
