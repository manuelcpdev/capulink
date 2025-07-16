<?php

namespace App\Http\Controllers;

use App\Http\Resources\LigazonUsuarioResource;
use App\Models\LigazonUsuario;
use App\Models\Perfil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\UserTrait;
use Illuminate\Support\Facades\Log;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Amosar perfil do usuario conectado
     */

    public function amosarConectado()
    {
        $user = User::where('name', Auth::user()->name)->first();

        if ($user) {
            $ligazons = LigazonUsuarioResource::collection(LigazonUsuario::with('ligazon')->where('user_id', Auth::id())->get());
            return response()->json([
                'name' => $user->name,
                'foto' => $user->perfil->foto,
                'visibilidade' => $user->perfil->visibilidade,
                'ligazons' => $ligazons,
            ], 200);
        }

        return response()->json([
            'mensaxe' => 'O usuario non está conectado',
            'error' => '',
        ], 400);
    }

    /**
     * Amosar o perfil de usuario según o nome
     */
    public function show($name)
    {
        $usuario = User::where('name', $name)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario non atopado'], 404);
        }

        if ($usuario->perfil->visibilidade == 'publico' || Auth::user()->admin || Auth::user()->name == $usuario->name) {
            //$ligazons = $usuario->ligazonsUsuario->where('agochado', 0);
            $ligazons = LigazonUsuarioResource::collection(LigazonUsuario::with('ligazon')->where('user_id', $usuario->id)->get());
            $ligazonsFiltradas = LigazonUsuarioResource::collection($ligazons)
                ->filter(function ($resource) {
                    return !empty($resource->resource) && $resource->toArray(request()) !== [];
                })->values()->toArray(request());

            Log::debug($ligazonsFiltradas);
            return response()->json([
                'mensaxe' => 'Acceso permitido',
                'name' => $usuario->name,
                'foto' => $usuario->perfil->foto,
                'ligazons' => $ligazonsFiltradas,
            ], 200);
        } else {
            return response()->json([
                'mensaxe' => 'Este perfil non é publico',
                'error' => '',
            ], 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perfil $perfil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perfil $perfil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perfil $perfil)
    {
        //
    }
}
