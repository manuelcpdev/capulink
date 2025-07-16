<?php

use App\Http\Controllers\LigazonUsuarioController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\comprobarRol;
use Illuminate\Support\Facades\Route;
Route::middleware([comprobarRol::class])->group(function () {
    Route::get('/ligazons/usuarios', [LigazonUsuarioController::class, 'index']);
    Route::get('/ligazons/usuarios/{id}', [LigazonUsuarioController::class, 'indexByUserID']);
    Route::get('/usuarios', [UserController::class, 'index']);
});
