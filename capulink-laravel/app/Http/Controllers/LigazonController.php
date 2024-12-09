<?php

namespace App\Http\Controllers;

use App\Models\Ligazon;
use App\Models\User;
use App\Models\RegexLigazonProhibida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LigazonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ligazon = Ligazon::all();
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
        /*
        // Obtemos as expresións regulares prohibidas
        $regexProhibidas = DB::table('regex_ligazons_prohibdas')->pluck('regex')->toArray();

        // Comprobamos se a ligazón coincide cunha expresión regular prohibida
        foreach ($regexProhibidas as $regex) {
            if (preg_match("/$regex/", $request->url)) {
                return response()->json(['error' => "A ligazón '{$request->url}' está prohibida."], 400);
            }
        }
        */
    }

    /**
     * Display the specified resource.
     */
    public function show(Ligazon $ligazon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ligazon $ligazon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ligazon $ligazon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ligazon $ligazon)
    {
        //
    }

/*
    public function crearLigazonDeUsuario(Request $request) {



        try {
            DB::beginTransaction();
            $ligazon = new Ligazon();
            $ligazon->categoria_id = $request->input('idCategoria');
            $ligazon->titulo = $request->input('tituloLigazon');
            $ligazon->descricion = $request->input('descricion');
            $ligazon->apropiado = $request->input('apropiado');
            $ligazon->url = $request->input('url');
            $ligazon->save();

            $user = User::where('id', Auth::user()->getAuthIdentifier())->first();
            $user->ligazons()->attach($ligazon->id, [
                'titulo' => $ligazon->titulo,
                'agochado' => $request->input('agochado'),
                'apropiado' => $ligazon->apropiado,
                'descricion' => $ligazon->descricion
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error ao crear a ligazón',
                'error' => $e->getMessage()
            ], 500);
        }
        return response()->json([
            'message' => 'Ligazón creada',
            'categoria' => $ligazon
        ]);
    }
    */

    public function crearLigazonDeUsuario(Request $request)
{
    // Validación de entrada
    $validator = Validator::make($request->all(), [
        'idCategoria' => 'required|integer|exists:categorias,id',
        'titulo' => 'required|string|max:255',
        'agochado' => 'required|boolean',
        'apropiado' => 'required|boolean',
        'url' => 'required|url',
        'descricion' => 'nullable|string|max:1000',
    ], [
        'idCategoria.required' => 'La categoría es obligatoria.',
        'titulo.required' => 'El título es obligatorio.',
        'url.required' => 'La URL es obligatoria.',
        'url.url' => 'La URL debe tener un formato válido.',
    ]);

    // Devolver errores de validación si existen
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors(),
        ], 422);
    }

    $validatedData = $validator->validated();

    try {
        DB::beginTransaction(); // Inicia la transacción

        // Buscar ligazón existente por URL
        $ligazon = Ligazon::where('url', $validatedData['url'])->first();

        if (!$ligazon) {
            // Crear nueva ligazón si no existe
            $ligazon = new Ligazon();
            $ligazon->categoria_id = $validatedData['idCategoria'];
            $ligazon->titulo = $validatedData['titulo'];
            $ligazon->descricion = $request->input('descricion', null);
            $ligazon->apropiado = $validatedData['apropiado'];
            $ligazon->url = $validatedData['url'];
            $ligazon->save();
        }

        // Adjuntar ligazón al usuario autenticado
        $user = User::findOrFail(Auth::id());
        $user->ligazons()->attach($ligazon->id, [
            'titulo' => $validatedData['titulo'],
            'agochado' => $validatedData['agochado'],
            'apropiado' => $validatedData['apropiado'],
            'descricion' => $request->input('descricion', null),
        ]);

        DB::commit(); // Confirmar la transacción

        return response()->json([
            'message' => 'Ligazón creada o asociada exitosamente',
            'ligazon' => $ligazon,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Error ao crear ou asociar a ligazón',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
