<?php

namespace App\Http\Controllers;

use App\Models\Etiqueta;
use App\Models\Ligazon;
use App\Models\User;
use App\Models\RegexLigazonProhibida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Grupo;

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

    public function crearLigazonDeUsuario(Request $request)
    {
        // Validación de entrada
        $validator = Validator::make($request->all(), [
            'idCategoria' => 'nullable|integer|exists:categorias,id',
            'titulo' => 'required|string|max:255',
            'agochado' => 'required|boolean',
            'apropiado' => 'required|boolean',
            'url' => 'required|url',
            'descricion' => 'nullable|string|max:1000',
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'string|max:50', // Cada etiqueta debe ser unha cadea de texto
        ], [
            'idCategoria.exists' => 'A categoría proporcionada non existe.',
            'titulo.required' => 'O título é obrigatorio.',
            'url.required' => 'A URL é obrigatoria.',
            'url.url' => 'A URL debe ter un formato válido.',
            'etiquetas.*.max' => 'Cada etiqueta pode ter un máximo de 50 caracteres.',
        ]);

        // Devolver erros de validación se existen
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        try {
            DB::beginTransaction(); // Inicia a transacción

            // Buscar ligazón existente por URL
            $ligazon = Ligazon::where('url', $validatedData['url'])->first();

            if (!$ligazon) {
                // Crear nova ligazón se non existe
                $ligazon = new Ligazon();
                $ligazon->categoria_id = $validatedData['idCategoria'] ?? null;
                $ligazon->titulo = $validatedData['titulo'];
                $ligazon->descricion = $request->input('descricion', null);
                $ligazon->apropiado = $validatedData['apropiado'];
                $ligazon->url = $validatedData['url'];
                $ligazon->save();
            }


            // Adxuntar ligazón ao usuario autenticado
            $user = User::findOrFail(Auth::id());

            $ligazonExisteUsuario = $user->ligazons()->wherePivot('ligazon_id', $ligazon->id)->exists();

            if ($ligazonExisteUsuario) {
                return response()->json([
                    'mensaxe' => 'Xa existe esta ligazón para este usuario.',
                    'error' => ['ligazon_usuario' => 'Xa existe esta ligazón para este usuario.'],
                ], 403);
            }

            $user->ligazons()->attach($ligazon->id, [
                'titulo' => $validatedData['titulo'],
                'agochado' => $validatedData['agochado'],
                'apropiado' => $validatedData['apropiado'],
                'descricion' => $request->input('descricion', null),
            ]);

            // Procesar etiquetas e asociar á ligazón
            $etiquetasInput = $validatedData['etiquetas'] ?? [];
            foreach ($etiquetasInput as $etiquetaTitulo) {
                $etiqueta = Etiqueta::where('titulo', $etiquetaTitulo)->first();
                if (!$etiqueta) {
                    //echo $etiquetaTitulo;
                    $etiqueta = new Etiqueta;
                    $etiqueta->titulo = $etiquetaTitulo;
                    $etiqueta->save();
                }

                // Insertar directamente na táboa intermedia
                DB::table('usuario_ligazon_etiqueta')->insert([
                    'user_id' => $user->id,
                    'ligazon_id' => $ligazon->id,
                    'etiqueta_id' => $etiqueta->id,
                    'apropiado' => true, // Aquí podes poñer o valor que queiras
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit(); // Confirmar a transacción

            return response()->json([
                'message' => 'Ligazón creada ou asociada exitosamente',
                'ligazon' => $ligazon,
                'etiquetas' => $etiquetasInput,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error ao crear ou asociar a ligazón',
                'error' => $e->getMessage(),
                //'etiquetas' => $etiquetasInput,
            ], 500);
        }
    }

    public function obterLigazonsUsuarioConectado()
    {
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user) {
            return response()->json([
                'mensaxe' => 'Non hai un usuario conectado',
                'error' => '',
            ], 403);
        }

        //$ligazons = $user->ligazons()->get();
        $ligazons = $user->ligazons;
        $ligazonsPivot = [];
        foreach($ligazons as $ligazon) {
            $ligazonsPivot[] = array_merge(
                $ligazon->pivot->toArray(),
            [
                'url' => $ligazon->url,
            ]);
        }
        return response()->json([
            'mensaxe' => 'Ligazóns do usuario obtidas.',
            'ligazons' => $ligazonsPivot,
        ]);
    }

    public function ObterLigazonsUsuario($name)
    {
        // Obter o usuario autenticado
        $usuarioAutenticado = Auth::user();

        // Buscar o usuario da URL ou o autenticado
        $usuario = $name ? User::where('name', trim($name))->first() : $usuarioAutenticado;

        if (!$usuario) {
            return response()->json([
                'mensaxe' => 'Non se atopou o usuario',
                'error' => true,
            ], 404);
        }

        // Verificar permisos
        $podeVerLigazons = false;
        if (
            $usuarioAutenticado &&
            (
                $usuarioAutenticado->admin ||
                $usuarioAutenticado->id === $usuario->id ||
                $usuario->perfil->visibilidade === 'publico'
            )
        ) {
            $podeVerLigazons = true;
        }

        if (!$podeVerLigazons) {
            return response()->json([
                'mensaxe' => 'Non tes permiso para ver estas ligazóns',
                'error' => true,
            ], 403);
        }

        // Obter ligazóns do usuario segundo os permisos
        $ligazonsUsuario = $usuario->ligazons()
            ->with(['categoria', 'etiquetasUsuario']) // Relacións
            ->when(
                !$usuarioAutenticado?->admin && $usuarioAutenticado?->id !== $usuario->id,
                function ($query) {
                    $query->wherePivot('agochado', false); // Excluir agochadas se non é admin ou o mesmo usuario
                }
            )
            ->get();

        // Estruturar a resposta
        echo $usuario->perfil;
        $resultado = [
            'usuario' => [
                'id' => $usuario->id,
                'name' => $usuario->name,
                'perfil_visibilidade' => $usuario->perfil()->visibilidade,
            ],
            'ligazons' => $ligazonsUsuario,
        ];

        return response()->json($resultado, 200);
    }





    public function obterLigazonsPorGrupo($grupoId)
    {
        // Obter o grupo
        $grupo = Grupo::find($grupoId);

        if (!$grupo) {
            return response()->json(['error' => 'Grupo non atopado'], 404);
        }

        // Obter o usuario autenticado
        $usuario = Auth::user();

        // Comprobar se o usuario é admin
        $isAdmin = $usuario && $usuario->admin;

        // Comprobar se o grupo é público ou privado
        $isPublico = $grupo->visibilidade == 'publico';

        // Comprobar se o usuario é membro do grupo
        $esMembro = $grupo->users()->where('user_id', $usuario->id)->exists();

        // Se o grupo é público ou o usuario é admin ou membro, devolver as ligazóns
        if ($isPublico || $isAdmin || $esMembro) {
            // Obter as ligazóns do grupo xunto coas súas etiquetas
            $ligazons = $grupo->ligazons()->with('etiquetas')->get();

            return response()->json([
                'ligazons' => $ligazons
            ], 200);
        }

        // Se o usuario non ten acceso
        return response()->json(['error' => 'Acceso denegado'], 403);
    }
}
