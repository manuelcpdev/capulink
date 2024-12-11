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

    public function updateLigazonDeUsuario(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'ligazon_id' => 'required|integer|exists:ligazons,id',
            'titulo' => 'nullable|string|max:255',
            'descricion' => 'nullable|string|max:1000',
            'agochado' => 'nullable|boolean',
            'apropiado' => 'nullable|boolean',
            'etiquetas_agregar' => 'nullable|array', // Etiquetas a agregar
            'etiquetas_agregar.*' => 'string|max:50',
            'etiquetas_eliminar' => 'nullable|array', // Etiquetas a eliminar
            'etiquetas_eliminar.*' => 'string|max:50',
        ], [
            'ligazon_id.required' => 'El ID de la ligazón es obligatorio.',
            'ligazon_id.exists' => 'La ligazón especificada no existe.',
            'titulo.string' => 'El título debe ser una cadena de texto.',
            'titulo.max' => 'El título no puede tener más de 255 caracteres.',
            'descricion.string' => 'La descripción debe ser una cadena de texto.',
            'descricion.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'etiquetas_agregar.*.max' => 'Cada etiqueta a agregar puede tener un máximo de 50 caracteres.',
            'etiquetas_eliminar.*.max' => 'Cada etiqueta a eliminar puede tener un máximo de 50 caracteres.',
        ]);

        // Retornar errores de validación si existen
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        try {
            DB::beginTransaction(); // Inicia la transacción

            // Obtener el usuario autenticado
            $user = User::findOrFail(Auth::id());

            // Verificar si la ligazón está asociada al usuario
            $ligazon = $user->ligazons()->where('ligazon_id', $validatedData['ligazon_id'])->first();
            if (!$ligazon) {
                return response()->json([
                    'message' => 'La ligazón no está asociada al usuario.',
                ], 404);
            }

            // Actualizar los datos de la tabla intermedia
            $pivotData = [];
            if (isset($validatedData['titulo'])) {
                $pivotData['titulo'] = $validatedData['titulo'];
            }
            if (isset($validatedData['descricion'])) {
                $pivotData['descricion'] = $validatedData['descricion'];
            }
            if (isset($validatedData['agochado'])) {
                $pivotData['agochado'] = $validatedData['agochado'];
            }
            if (isset($validatedData['apropiado'])) {
                $pivotData['apropiado'] = $validatedData['apropiado'];
            }

            if (!empty($pivotData)) {
                $user->ligazons()->updateExistingPivot($validatedData['ligazon_id'], $pivotData);
            }

            // Gestionar etiquetas
            if (isset($validatedData['etiquetas_agregar'])) {
                foreach ($validatedData['etiquetas_agregar'] as $etiquetaTitulo) {
                    $etiqueta = Etiqueta::firstOrCreate(['titulo' => $etiquetaTitulo]);

                    // Insertar relación en la tabla intermedia
                    DB::table('usuario_ligazon_etiqueta')->insertOrIgnore([
                        'user_id' => $user->id,
                        'ligazon_id' => $ligazon->id,
                        'etiqueta_id' => $etiqueta->id,
                        'apropiado' => true, // Puedes ajustar este valor según sea necesario
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if (isset($validatedData['etiquetas_eliminar'])) {
                foreach ($validatedData['etiquetas_eliminar'] as $etiquetaTitulo) {
                    $etiqueta = Etiqueta::where('titulo', $etiquetaTitulo)->first();
                    if ($etiqueta) {
                        // Eliminar la relación en la tabla intermedia
                        DB::table('usuario_ligazon_etiqueta')
                            ->where('user_id', $user->id)
                            ->where('ligazon_id', $ligazon->id)
                            ->where('etiqueta_id', $etiqueta->id)
                            ->delete();

                        // Eliminar la etiqueta si no está asociada a ninguna otra ligazón
                        if (!$etiqueta->ligazons()->exists()) {
                            $etiqueta->delete();
                        }
                    }
                }
            }

            DB::commit(); // Confirmar la transacción

            return response()->json([
                'message' => 'La ligazón fue actualizada correctamente.',
                'ligazon' => $ligazon,
                'etiquetas' => $ligazon->etiquetas->pluck('titulo'), // Retornar las etiquetas actualizadas
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir cambios si ocurre un error
            return response()->json([
                'message' => 'Error al actualizar la ligazón.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteLigazonsDeUsuario(Request $request)
    {
        // Validación de entrada
        $validator = Validator::make($request->all(), [
            'ligazons' => 'required|array', // Debe ser un array
            'ligazons.*' => 'integer|exists:ligazons,id', // Cada ID debe existir
        ], [
            'ligazons.required' => 'Es necesario proporcionar las ligazons a eliminar.',
            'ligazons.*.exists' => 'Algunas de las ligazons especificadas no existen.',
        ]);

        var_dump($request->all());

        // Retornar errores de validación si existen
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        try {
            DB::beginTransaction(); // Inicia la transacción

            // Obtener el usuario autenticado
            $user = User::findOrFail(Auth::id());

            // Obtener las ligazons del usuario que se desean eliminar
            $ligazonsAEliminar = $user->ligazons()
                ->whereIn('ligazon_id', $validatedData['ligazons'])
                ->get();

            if ($ligazonsAEliminar->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron ligazons asociadas al usuario para eliminar.',
                ], 404);
            }

            // Eliminar las relaciones en la tabla intermedia (usuario-ligazón)
            foreach ($ligazonsAEliminar as $ligazon) {
                $user->ligazons()->detach($ligazon->id);

                // Eliminar etiquetas asociadas al usuario-ligazón
                DB::table('usuario_ligazon_etiqueta')
                    ->where('user_id', $user->id)
                    ->where('ligazon_id', $ligazon->id)
                    ->delete();

                // Verificar si la ligazón queda sin usuarios asociados
                if (!$ligazon->usuarios()->exists()) {
                    // Eliminar etiquetas asociadas si ya no tienen relaciones
                    foreach ($ligazon->etiquetasUsuario as $etiqueta) {
                        if (!$etiqueta->ligazons()->exists()) {
                            $etiqueta->delete();
                        }
                    }

                    // Eliminar la ligazón
                    $ligazon->delete();
                }
            }

            DB::commit(); // Confirmar la transacción

            return response()->json([
                'message' => 'Las ligazons fueron eliminadas correctamente.',
                'ligazons_eliminadas' => $ligazonsAEliminar->pluck('id'), // IDs de las ligazons eliminadas
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir cambios si ocurre un error
            return response()->json([
                'message' => 'Error al eliminar las ligazons.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

public function crearLigazonDeGrupo(Request $request)
{
    // Validación de entrada
    $validator = Validator::make($request->all(), [
        'grupo_id' => 'required|integer|exists:grupos,id',
        'idCategoria' => 'nullable|integer|exists:categorias,id',
        'titulo' => 'required|string|max:255',
        'agochado' => 'required|boolean',
        'apropiado' => 'required|boolean',
        'descricion' => 'nullable|string|max:1000',
        'url' => 'required|url',
        'etiquetas' => 'nullable|array',
        'etiquetas.*' => 'string|max:50',
    ], [
        'grupo_id.required' => 'O ID do grupo é obrigatorio.',
        'grupo_id.exists' => 'O grupo especificado non existe.',
        'titulo.required' => 'O título no é obrigatorio.',
        'url.required' => 'A URL é obrigatoria.',
        'url.url' => 'A URL debe ter un formato válido.',
        'etiquetas.*.max' => 'Cada etiqueta pode ter un máximo de 50 caracteres.',
    ]);

        // Retornar erros de validación se existen
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

    try {
        DB::beginTransaction(); // Inicia a transacción

        // Verificar que o usuario é membro do grupo
        $grupo = Grupo::with('users')->findOrFail($validatedData['grupo_id']);
        $userId = Auth::id();

        if (!$grupo->users->pluck('id')->contains($userId) && $grupo->user_id !== $userId) {
            return response()->json([
                'message' => 'Non tes permisos para engadir ligazóns a este grupo.',
            ], 403);
        }

        // Buscar ou crear a ligazón
        $ligazon = Ligazon::where('url', $validatedData['url'])->first();
        if (!$ligazon) {
            $ligazon = new Ligazon;
            $ligazon->titulo = $validatedData['titulo'];
            $ligazon->descricion = $validatedData['descricion'];
            $ligazon->apropiado = $validatedData['apropiado'];
            $ligazon->url = $validatedData['url'];
            $ligazon->save();
        }

       // Asociar a ligazón ao grupo con datos adicionales
       $grupo->ligazons()->attach($ligazon->id, [
        'titulo' => $validatedData['titulo'],
        'descricion' => $validatedData['descricion'] ?? null,
        'agochado' => $validatedData['agochado'],
        'apropiado' => $validatedData['apropiado'],
        'created_at' => now(),
        'updated_at' => now(),
        ]);

        // Procesar etiquetas
        $etiquetasInput = $validatedData['etiquetas'] ?? [];
        foreach ($etiquetasInput as $etiquetaTitulo) {
            $etiqueta = Etiqueta::where('titulo', $etiquetaTitulo)->first();
            if(!$etiqueta) {
                $etiqueta = new Etiqueta;
                $etiqueta->titulo = $etiquetaTitulo;
                $etiqueta->save();
            }
            // Asociar etiqueta á ligazón e ao grupo
            DB::table('grupo_ligazon_etiqueta')->insertOrIgnore([
                'grupo_id' => $grupo->id,
                'ligazon_id' => $ligazon->id,
                'etiqueta_id' => $etiqueta->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::commit(); // Confirmar a transacción

        return response()->json([
            'message' => 'Ligazón creada e asociada ao grupo exitosamente.',
            'ligazon' => $ligazon,
            'grupo' => $grupo,
        ]);
    } catch (\Exception $e) {
        DB::rollBack(); // Revertir cambios si ocurre un error
        return response()->json([
            'message' => 'Error ao crear ou asociar a ligazón ao grupo.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function updateLigazonDeGrupo(Request $request){
        // Validar os datos de entrada
        $validator = Validator::make($request->all(), [
            'grupo_id' => 'required|integer|exists:grupos,id',
            'ligazon_id' => 'required|integer|exists:ligazons,id',
            'titulo' => 'nullable|string|max:255',
            'descricion' => 'nullable|string|max:1000',
            'agochado' => 'nullable|boolean',
            'apropiado' => 'nullable|boolean',
            'etiquetas_agregar' => 'nullable|array', // Etiquetas para engadir
            'etiquetas_agregar.*' => 'string|max:50',
            'etiquetas_eliminar' => 'nullable|array', // Etiquetas para eliminar
            'etiquetas_eliminar.*' => 'string|max:50',
        ], [
            'grupo_id.required' => 'O ID do grupo é obrigatorio.',
            'grupo_id.exists' => 'O grupo especificado non existe.',
            'ligazon_id.required' => 'O ID da ligazón é obrigatorio.',
            'ligazon_id.exists' => 'A ligazón especificada non existe.',
            'titulo.string' => 'O título debe ser unha cadea de texto.',
            'titulo.max' => 'O título non pode ter máis de 255 caracteres.',
            'descricion.string' => 'A descrición debe ser unha cadea de texto.',
            'descricion.max' => 'A descrición non pode ter máis de 1000 caracteres.',
            'etiquetas_agregar.*.max' => 'Cada etiqueta para engadir pode ter un máximo de 50 caracteres.',
            'etiquetas_eliminar.*.max' => 'Cada etiqueta para eliminar pode ter un máximo de 50 caracteres.',
        ]);

        // Retornar errores de validación si existen
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        try {
            DB::beginTransaction(); // Inicia a transacción

            // Verificar o grupo
            $grupo = Grupo::with('users')->findOrFail($validatedData['grupo_id']);
            $userId = Auth::id();

            if (!$grupo->users->pluck('id')->contains($userId) && $grupo->user_id !== $userId) {
                return response()->json([
                    'message' => 'Non tes permisos para modificar ligazóns neste grupo.',
                ], 403);
            }

            // Buscar a ligazón
            $ligazon = $grupo->ligazons()->where('ligazons.id', $validatedData['ligazon_id'])->first();
            if (!$ligazon) {
                return response()->json([
                    'message' => 'A ligazón especificada non está asociada a este grupo.',
                ], 404);
            }

            // Actualizar os campos da relación no pivote
            $pivotData = [];
            if (isset($validatedData['titulo'])) {
                $pivotData['titulo'] = $validatedData['titulo'];
            }

            if (isset($validatedData['descricion'])) {
                $pivotData['descricion'] = $validatedData['descricion'];
            }

            if (isset($validatedData['agochado'])) {
                $pivotData['agochado'] = $validatedData['agochado'];
            }

            if (isset($validatedData['apropiado'])) {
                $pivotData['apropiado'] = $validatedData['apropiado'];
            }

            if (!empty($pivotData)) {
                $pivotData['updated_at'] = now();
                $grupo->ligazons()->updateExistingPivot($ligazon->id, $pivotData);
            }

            // Procesar etiquetas para engadir
            if (isset($validatedData['etiquetas_agregar'])) {
                foreach ($validatedData['etiquetas_agregar'] as $etiquetaTitulo) {
                    $etiqueta = Etiqueta::where('titulo', $etiquetaTitulo)->first();
                    if (!$etiqueta) {
                        $etiqueta = new Etiqueta;
                        $etiqueta->titulo = $etiquetaTitulo;
                        $etiqueta->save();
                    }
                    DB::table('grupo_ligazon_etiqueta')->insertOrIgnore([
                        'grupo_id' => $grupo->id,
                        'ligazon_id' => $ligazon->id,
                        'etiqueta_id' => $etiqueta->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Procesar etiquetas para eliminar
            if (isset($validatedData['etiquetas_eliminar'])) {
                foreach ($validatedData['etiquetas_eliminar'] as $etiquetaTitulo) {
                    $etiqueta = Etiqueta::where('titulo', $etiquetaTitulo)->first();
                    if ($etiqueta) {
                        DB::table('grupo_ligazon_etiqueta')
                            ->where('grupo_id', $grupo->id)
                            ->where('ligazon_id', $ligazon->id)
                            ->where('etiqueta_id', $etiqueta->id)
                            ->delete();
                    }
                }
            }

            DB::commit(); // Confirmar a transacción

            return response()->json([
                'message' => 'Ligazón modificada correctamente.',
                'ligazon' => $ligazon,
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Reverter cambios en caso de erro
            return response()->json([
                'message' => 'Erro ao modificar a ligazón.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


public function deleteLigazonsDeGrupo(Request $request)
{
    // Validar os datos de entrada
    $validator = Validator::make($request->all(), [
        'grupo_id' => 'required|integer|exists:grupos,id',
        'ligazon_ids' => 'required|array',
        'ligazon_ids.*' => 'integer|exists:ligazons,id',
    ], [
        'grupo_id.required' => 'O ID do grupo é obrigatorio.',
        'grupo_id.exists' => 'O grupo especificado non existe.',
        'ligazon_ids.required' => 'Debes proporcionar polo menos menos unha ligazón para eliminar.',
        'ligazon_ids.*.exists' => 'Unha ou máis ligazons non existen.',
    ]);

    // Retornar errores de validación se existen
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors(),
        ], 422);
    }

        $validatedData = $validator->validated();

    try {
        DB::beginTransaction(); // Inicia a transacción

        // Verificar que o usuario é miembro do grupo
        $grupo = Grupo::with('users')->findOrFail($validatedData['grupo_id']);
        $userId = Auth::id();

        if (!$grupo->users->pluck('id')->contains($userId) && $grupo->user_id !== $userId) {
            return response()->json([
                'message' => 'Non tes permisos para eliminar ligazons de este grupo.',
            ], 403);
        }

            $ligazonIds = $validatedData['ligazon_ids'];

        // Verificar que as ligazons están asociadas ao grupo
        $ligazonsAsociadas = $grupo->ligazons()->whereIn('ligazon_id', $ligazonIds)->pluck('ligazon_id')->toArray();

        if (empty($ligazonsAsociadas)) {
            return response()->json([
                'message' => 'Ningunha das ligazóns proporcionadas está asociada a este grupo.',
            ], 404);
        }

        // Eliminar asociacións de ligazóns co grupo
        $grupo->ligazons()->detach($ligazonsAsociadas);

        // Eliminar asociacións de etiquetas co grupo e coas ligazóns
        foreach ($ligazonsAsociadas as $ligazonId) {
            DB::table('grupo_ligazon_etiqueta')
                ->where('grupo_id', $grupo->id)
                ->where('ligazon_id', $ligazonId)
                ->delete();
        }


        DB::commit(); // Confirmar a transacción

        return response()->json([
            'message' => 'As ligazons foron eliminadas do grupo exitosamente.',
            'ligazon_ids_eliminadas' => $ligazonsAsociadas,
        ]);
    } catch (\Exception $e) {
        DB::rollBack(); // Revertir cambios se ocurre un error
        return response()->json([
            'message' => 'Error ao eliminar as ligazóns do grupo.',
            'error' => $e->getMessage(),
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
        foreach ($ligazons as $ligazon) {
            foreach ($ligazon->etiquetasUsuario as $etiqueta) {
            }
            $ligazonsPivot[] = array_merge(
                $ligazon->pivot->toArray(),
                [
                    'url' => $ligazon->url,
                    'etiquetas' => $ligazon->etiquetasUsuario,
                ]
            );
        }
        return response()->json([
            'mensaxe' => 'Ligazóns do usuario obtidas.',
            'ligazons' => $ligazonsPivot,
        ]);
    }

    public function obterLigazonsUsuario($name)
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
        $resultado = [
            'usuario' => [
                'id' => $usuario->id,
                'name' => $usuario->name,
                'perfil_visibilidade' => $usuario->perfil,
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
