<?php

use App\Http\Controllers\ConexionController;
use App\Http\Controllers\RexistroController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AutenticacionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::post('rexistro', [AutenticacionController::class, 'rexistrar']);

Route::post('conexion', [AutenticacionController::class, 'conectar']);

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
