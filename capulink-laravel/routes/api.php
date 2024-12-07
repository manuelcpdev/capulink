<?php
use App\Http\Controllers\RexistroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::post('rexistro', [RexistroController::class, 'store']);
//Route::get('rexistro', [RexistroController::class, 'index']);
