<?php
use App\Http\Controllers\RexistroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacionController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


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
Route::post('/login-test', function(Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('name', $request->name)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        //throw ValidationException::withMessages([
        //    'name' => ['The provided credentials are incorrect.'],
        //]);
        return response()->json(['mensaxe' => "error"], 401);
    }

    return $user->createToken("UnToken")->plainTextToken;
});
