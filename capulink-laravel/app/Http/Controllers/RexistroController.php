<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RexistroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(['test']);
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
        $validated = $request->validate([
            'usuario' => 'required',
            'contrasinal' => 'required|min:6',
            'email' => 'required',
        ]);
        $data = $request->except('contrasinal');
        $formData = [
            'name' => $request->input('usuario'),
            'password' => Hash::make($request->input('contrasinal')),
            'email' => $request->email
        ];
        User::create($formData);
        return response()->json([
            $request->input('usuario'),
            $request->input('contrasinal'),
            $request->array,
        ], 200);
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
