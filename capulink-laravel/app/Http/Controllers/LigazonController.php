<?php

namespace App\Http\Controllers;

use App\Models\Ligazon;
use App\Models\User;
use App\Models\RegexLigazonProhibida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


    public function crearLigazonDeUsuario($request) {

        try {
            DB::beginTransaction(); // Inicia la transacción
            $ligazon = new Ligazon();
            $ligazon->categoria_id = $request->input('idCategoria');
            $ligazon->titulo = $request->input('tituloLigazon');
            $ligazon->descricion = $request->input('descricion');
            $ligazon->apropiado = $request->input('apropiado');
            $ligazon->visibilidade = $request->input('visibilidade');
            $ligazon->url = $request->input('url');
            $ligazon->save();

            $user = Auth::getUser();
            $user = User::where('id', $user->id)->first();
            $user->ligazons()->attach($ligazon->id, [
                'agochado' => $ligazon->visibilidade,
                'apropiado' => $ligazon->apropiado,
                'descricion' => $ligazon->descricion
            ]);

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
}
