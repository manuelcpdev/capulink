<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
//use App\Models\Perfil;  // Importar o modelo Perfil
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AutenticacionController extends Controller
{
    public function rexistrar(Request $request)
    {

        $regras = [
            'usuario' => 'required|unique:users,name',
            'contrasinal' => 'required|min:6',
            'email' => 'required|unique:users,email',
        ];

        $validator = Validator::make($request->all(), $regras);

        // Comprobar se hai erros de validación
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );  // Código 422 para erros de validación
        }

        /* Datos procesados */
        $formData = [
            'name' => $request->input('usuario'),
            'password' => Hash::make($request->input('contrasinal')),
            'email' => $request->input('email'),
        ];

        /* Crear o usuario se non houbo erros */
        $user = User::create($formData);

        /* Crea un perfil de usuario asociado ó novo usuario */
        $perfil = $user->perfil()->create([
            'foto' => '',  // Foto por defecto
            'visibilidade' => 'publico',  // Visibilidade por defecto
        ]);


        //Esta maneira tamén é válida
        /*
        $perfil = Perfil::create([
            'user_id' => $user->id,
            'foto' => '',  // Foto por defecto
            'visibilidade' => 'publico',  // Visibilidade por defecto
        ]);
        */

        /* Inicia sesión co novo usuario */
        Auth::login($user);

        /* Se todo foi ben, devolver unha resposta 201 */
        return response()->json([
            'message' => 'Usuario rexistrado correctamente',
            'usuario' => $user,
            'perfil' => $perfil,
        ], 201);  // Código de estado 201 para recursos creados
    }

    public function conectar(Request $request)
    {
        // Validar os datos do formulario
        $validator = Validator::make($request->all(), [
            'usuario' => ['required', 'string'],
            'contrasinal' => ['required', 'string'],
        ]);

        // Se a validación falla, devolver os erros
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            ); // 422 Unprocessable Entity
        }

        // Obter as credenciais validadas
        $credentials = $validator->validated();

        // Intentar autenticar o usuario
        if (Auth::attempt(['name' => $credentials['usuario'], 'password' => $credentials['contrasinal']])) {
            $request->session()->regenerate();

            return response()->json([
                'mensaxe' => 'Inicio de sesión exitoso',
                'usuario' => Auth::user(),
            ], 200);
        }

        // Se as credenciais son incorrectas, devolver erro
        return response()->json([
            'usuario' => 'As credenciais proporcionadas non son correctas.'
        ], 401);
    }
}
