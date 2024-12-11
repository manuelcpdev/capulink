<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class GrupoController extends Controller
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
    // Validación de entrada
    $validator = Validator::make($request->all(), [
        'titulo' => 'required|string|max:255',
        'descricion' => 'nullable|string|max:1000',
        'apropiado' => 'required|boolean',
        'etiquetas' => 'nullable|array',
        'etiquetas.*' => 'string|max:50', // Cada etiqueta debe ser una cadena de texto
    ], [
        'titulo.required' => 'O título do grupo é obrigatorio.',
        'apropiado.required' => 'Debe especificar se o grupo é apropiado.',
        'etiquetas.*.max' => 'Cada etiqueta pode ter un máximo de 50 caracteres.',
    ]);

    // Devolver errores de validación si existen
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Erro de validación',
            'errors' => $validator->errors(),
        ], 422);
    }

    $validatedData = $validator->validated();

    try {
        DB::beginTransaction(); // Inicia a transacción

        // Crear o grupo
        $grupo = new Grupo();
        $grupo->titulo = $validatedData['titulo'];
        $grupo->descricion = $validatedData['descricion'] ?? null;
        $grupo->apropiado = $validatedData['apropiado'];
        $grupo->user_id = Auth::id(); // Usuario autenticado como creador
        $grupo->save();

        // Procesar etiquetas y asociarlas al grupo
        $etiquetasInput = $validatedData['etiquetas'] ?? [];
        foreach ($etiquetasInput as $etiquetaTitulo) {
            // Buscar ou crear a etiqueta
            $etiqueta = Etiqueta::where('titulo', $etiquetaTitulo)->first();
            if(!$etiqueta) {
                //echo $etiquetaTitulo;
                $etiqueta = new Etiqueta;
                $etiqueta->titulo = $etiquetaTitulo;
                $etiqueta->save();
            }

            // Asociar a etiqueta co grupo
            $grupo->etiquetas()->attach($etiqueta->id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::commit(); // Confirmar a transacción

        return response()->json([
            'message' => 'Grupo creado exitosamente',
            'grupo' => $grupo,
            'etiquetas' => $etiquetasInput,
        ]);
    } catch (\Exception $e) {
        DB::rollBack(); // Reverter cambios se ocorre un erro
        return response()->json([
            'message' => 'Erro ao crear o grupo',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        //
    }

    public function joinGrupo(Request $request) {

        // Validar os datos de entrada
        $validator = Validator::make($request->all(), [
            'grupo_id' => 'required|integer|exists:grupos,id',
        ], [
            'grupo_id.required' => 'O ID do grupo é obrigatorio.',
            'grupo_id.exists' => 'O grupo especificado non existe.',
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

            // Buscar o grupo
            $grupo = Grupo::findOrFail($validatedData['grupo_id']);

            // Obter o ID do usuario autenticado
            $userId = Auth::id();

            // Asociar o usuario ao grupo
            $grupo->users()->syncWithoutDetaching($userId);

            DB::commit(); // Confirmar a transacción

            return response()->json([
                'message' => 'Usuario asociado ao grupo correctamente.',
                'grupo' => $grupo,
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Reverter cambios se ocorre un erro
            return response()->json([
                'message' => 'Erro ao asociar o usuario ao grupo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function forfeitGrupo(Request $request) {

        // Validar os datos de entrada
        $validator = Validator::make($request->all(), [
            'grupo_id' => 'required|integer|exists:grupos,id',
        ], [
            'grupo_id.required' => 'O ID do grupo é obrigatorio.',
            'grupo_id.exists' => 'O grupo especificado non existe.',
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

            // Buscar o grupo
            $grupo = Grupo::findOrFail($validatedData['grupo_id']);

            // Obter o ID do usuario autenticado
            $userId = Auth::id();

            // Verificar se o usuario pertence ao grupo
            if (!$grupo->users()->where('user_id', $userId)->exists()) {
                return response()->json([
                    'message' => 'O usuario non pertence a este grupo.',
                ], 404);
            }

            // Eliminar a relación entre o usuario e o grupo
            $grupo->users()->detach($userId);

            DB::commit(); // Confirmar a transacción

            return response()->json([
                'message' => 'Saíches do grupo correctamente.',
                'grupo' => $grupo,
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Reverter cambios se ocorre un erro
            return response()->json([
                'message' => 'Erro ao saír do grupo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteGrupo(Request $request)
    {
        // Validar os datos de entrada
        $validator = Validator::make($request->all(), [
            'grupo_id' => 'required|integer|exists:grupos,id',
        ], [
            'grupo_id.required' => 'O ID do grupo é obrigatorio.',
            'grupo_id.exists' => 'O grupo especificado non existe.',
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

            // Buscar o grupo
            $grupo = Grupo::findOrFail($validatedData['grupo_id']);

            // Verificar se o usuario autenticado é o creador do grupo
            if ($grupo->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Só o creador do grupo pode eliminalo.',
                ], 403);
            }

            // Eliminar o grupo (tamén elimina relacións coa táboa intermedia)
            $grupo->delete();

            DB::commit(); // Confirmar a transacción

            return response()->json([
                'message' => 'O grupo foi eliminado correctamente.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Reverter cambios se ocorre un erro
            return response()->json([
                'message' => 'Erro ao eliminar o grupo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateGrupo(Request $request){
        // Validar os datos de entrada
        $validator = Validator::make($request->all(), [
            'grupo_id' => 'required|integer|exists:grupos,id',
            'titulo' => 'nullable|string|max:255',
            'descricion' => 'nullable|string|max:1000',
            'visibilidade' => 'nullable|in:publico,privado',
            'etiquetas_agregar' => 'nullable|array', // Etiquetas para engadir
            'etiquetas_agregar.*' => 'string|max:50',
            'etiquetas_eliminar' => 'nullable|array', // Etiquetas para eliminar
            'etiquetas_eliminar.*' => 'string|max:50',
        ], [
            'grupo_id.required' => 'O ID do grupo é obrigatorio.',
            'grupo_id.exists' => 'O grupo especificado non existe.',
            'titulo.string' => 'O título debe ser unha cadea de texto.',
            'titulo.max' => 'O título non pode ter máis de 255 caracteres.',
            'descricion.string' => 'A descrición debe ser unha cadea de texto.',
            'descricion.max' => 'A descrición non pode ter máis de 1000 caracteres.',
            'visibilidade.in' => 'A visibilidade debe ser "publico" ou "privado".',
            'etiquetas_agregar.*.max' => 'Cada etiqueta para engadir pode ter un máximo de 50 caracteres.',
            'etiquetas_eliminar.*.max' => 'Cada etiqueta para eliminar pode ter un máximo de 50 caracteres.',
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

            // Buscar o grupo
            $grupo = Grupo::findOrFail($validatedData['grupo_id']);

            // Verificar se o usuario autenticado é o creador do grupo
            if ($grupo->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'Só o creador do grupo pode modificalo.',
                ], 403);
            }

            // Modificar os campos permitidos
            if (isset($validatedData['titulo'])) {
                $grupo->titulo = $validatedData['titulo'];
            }

            if (isset($validatedData['descricion'])) {
                $grupo->descricion = $validatedData['descricion'];
            }

            if (isset($validatedData['visibilidade'])) {
                $grupo->visibilidade = $validatedData['visibilidade'];
            }

            // Actualizar o timestamp automaticamente
            $grupo->updated_at = now();

            // Actualizar etiquetas
            if (isset($validatedData['etiquetas_agregar'])) {
                foreach ($validatedData['etiquetas_agregar'] as $etiquetaTitulo) {
                    $etiqueta = Etiqueta::where('titulo', $etiquetaTitulo)->first();
                    if(!$etiqueta) {
                        $etiqueta = new Etiqueta;
                        $etiqueta->titulo = $etiquetaTitulo;
                        $etiqueta->save();
                    }
                    $grupo->etiquetas()->syncWithoutDetaching($etiqueta->id);
                }
            }

            if (isset($validatedData['etiquetas_eliminar'])) {
                foreach ($validatedData['etiquetas_eliminar'] as $etiquetaTitulo) {
                    $etiqueta = Etiqueta::where('titulo', $etiquetaTitulo)->first();
                    if ($etiqueta) {
                        // Eliminar a relación do grupo coa etiqueta
                        $grupo->etiquetas()->detach($etiqueta->id);

                        // Eliminar a etiqueta se non está asociada a ningún outro grupo
                        if (!$etiqueta->grupos()->exists() && !$etiqueta->ligazons()->exists()) {
                            $etiqueta->delete();
                        }
                    }
                }
            }

            // Gardar os cambios no grupo
            $grupo->save();

            DB::commit(); // Confirmar a transacción

            return response()->json([
                'message' => 'O grupo foi modificado correctamente.',
                'grupo' => $grupo,
                'etiquetas' => $grupo->etiquetas->pluck('titulo'), // Retornar as etiquetas actualizadas
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Reverter cambios se ocorre un erro
            return response()->json([
                'message' => 'Erro ao modificar o grupo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getGruposUsuario()
    {
        try {
            // Obter o usuario autenticado
            $usuarioId = Auth::id();

            // Grupos públicos
            $gruposPublicos = Grupo::where('visibilidade', 'publico')->with('etiquetas')->get();

            // Grupos dos que é creador
            $gruposCreados = Grupo::where('user_id', $usuarioId)->with('etiquetas')->get();

            // Grupos nos que é participante
            $gruposParticipados = Grupo::whereHas('users', function ($query) use ($usuarioId) {
                $query->where('user_id', $usuarioId);
            })->with('etiquetas')->get();

            // Unir todos os grupos sen duplicados
            $todosGrupos = $gruposPublicos
                ->merge($gruposCreados)
                ->merge($gruposParticipados)
                ->unique('id'); // Evitar duplicados

            return response()->json([
                'message' => 'Grupos do usuario recuperados correctamente.',
                'grupos' => $todosGrupos,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar os grupos do usuario.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getGruposOfCreator()
    {
        try {
            // Obter o usuario autenticado
            $usuarioId = Auth::id();

            // Recuperar os grupos creados polo usuario
            $gruposCreados = Grupo::where('user_id', $usuarioId)->with('etiquetas')->get();

            return response()->json([
                'message' => 'Grupos creados polo usuario recuperados correctamente.',
                'grupos' => $gruposCreados,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar os grupos creados polo usuario.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getGruposWithMembership()
    {
        try {
            // Obter o ID do usuario autenticado
            $usuario = User::where('id', Auth::id())->first();

            $gruposComoMembro = $usuario->grupos()->with('etiquetas')->get();
            return response()->json([
                'message' => 'Grupos nos que o usuario é membro recuperados correctamente.',
                'grupos' => $gruposComoMembro
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar os grupos nos que o usuario é membro.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getGruposPublicos()
    {
        try {
            // Recuperar todos os grupos con visibilidade pública
            $gruposPublicos = Grupo::where('visibilidade', 'publico')->with('etiquetas')->get();

            return response()->json([
                'message' => 'Grupos públicos recuperados correctamente.',
                'grupos' => $gruposPublicos,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar os grupos públicos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getGrupo($id){
        try {
            // Buscar o grupo polo seu ID
            $grupo = Grupo::findOrFail($id);

            // Verificar se o grupo é público
            if ($grupo->visibilidade === 'publico') {
                return response()->json([
                    'message' => 'Grupo público recuperado correctamente.',
                    'grupo' => $grupo,
                    'etiquetas' => $grupo->etiquetas()->get()
                ]);
            }

            // Verificar se o usuario conectado é o creador, un administrador ou un membro
            $user = Auth::user();

            $isCreador = $grupo->user_id === $user->id; // O usuario é o creador?
            $isMiembro = $grupo->users()->where('user_id', $user->id)->exists(); // O usuario é membro?

            if ($isCreador || $isMiembro || $user->is_admin) {
                return response()->json([
                    'message' => 'Acceso permitido. Grupo recuperado correctamente.',
                    'grupo' => $grupo,
                    'etiquetas' => $grupo->etiquetas()->get()
                ]);
            }

            // Se non cumpre ningunha condición, devolver erro de acceso
            return response()->json([
                'message' => 'Acceso denegado. Non tes permisos para ver este grupo.',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar o grupo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
