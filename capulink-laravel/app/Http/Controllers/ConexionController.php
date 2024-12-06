<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

class ConexionController extends Controller
{

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'usuario' => ['required'],
            'contrasinal' => ['required'],
        ]);

        if (Auth::attempt(['name' => $credentials['usuario'], 'password' => $credentials['contrasinal']])) {
            $request->session()->regenerate();
            return response()->json([
                'usuario' => Auth::user(),
            ], 200);
        }
        return response()->json([
            'erro' => 'Houbo un erro no intento de inicio de sesión',
        ], 401);
    }
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
