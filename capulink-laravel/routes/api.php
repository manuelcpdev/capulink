<?php
use App\Http\Controllers\RexistroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::post('rexistro', [RexistroController::class, 'store']);
//Route::get('rexistro', [RexistroController::class, 'index']);
Route::post('/conexion', [AutenticacionController::class, 'conectar']);
    Route::any('/login', function() {
        return response()->json([
            'conectado' => 'false'
        ], 403);
    });
