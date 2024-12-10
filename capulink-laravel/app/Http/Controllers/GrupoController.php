<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
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
        ], [
            'titulo.required' => 'El título del grupo es obligatorio.',
            'apropiado.required' => 'Debe especificar si el grupo es apropiado.',
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

            // Crear el grupo
            $grupo = new Grupo();
            $grupo->titulo = $validatedData['titulo'];
            $grupo->descricion = $validatedData['descricion'] ?? null;
            $grupo->apropiado = $validatedData['apropiado'];
            $grupo->user_id = Auth::id(); // Usuario autenticado como creador
            $grupo->save();

            DB::commit(); // Confirmar la transacción

            return response()->json([
                'message' => 'Grupo creado exitosamente',
                'grupo' => $grupo,
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir cambios si ocurre un error
            return response()->json([
                'message' => 'Error ao crear o grupo',
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
    ], [
        'grupo_id.required' => 'O ID do grupo é obrigatorio.',
        'grupo_id.exists' => 'O grupo especificado non existe.',
        'titulo.string' => 'O título debe ser unha cadea de texto.',
        'titulo.max' => 'O título non pode ter máis de 255 caracteres.',
        'descricion.string' => 'A descrición debe ser unha cadea de texto.',
        'descricion.max' => 'A descrición non pode ter máis de 1000 caracteres.',
        'visibilidade.in' => 'A visibilidade debe ser "publico" ou "privado".',
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

        // Actualizar o timestamp automáticamente
        $grupo->updated_at = now();

        // Gardar os cambios
        $grupo->save();

        DB::commit(); // Confirmar a transacción

        return response()->json([
            'message' => 'O grupo foi modificado correctamente.',
            'grupo' => $grupo,
        ]);
    } catch (\Exception $e) {
        DB::rollBack(); // Reverter cambios se ocorre un erro
        return response()->json([
            'message' => 'Erro ao modificar o grupo.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
