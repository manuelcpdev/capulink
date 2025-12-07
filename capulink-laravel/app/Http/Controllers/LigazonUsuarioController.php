<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLigazonRequest;
use App\Http\Requests\StoreLigazonUsuarioRequest;
use App\Http\Requests\UpdateLigazonUsuarioRequest;
use App\Http\Resources\LigazonResource;
use App\Http\Resources\LigazonUsuarioResource;
use App\Models\Etiqueta;
use App\Models\Ligazon;
use App\Models\LigazonUsuario;
use App\Models\LigazonGrupo;
use App\Models\LigazonUsuarioEtiqueta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class LigazonUsuarioController extends Controller
{
    protected $user;
    public function __construct(protected LigazonUsuario $ligazon)
    {
        JsonResource::withoutWrapping();
        $this->user = Auth::user() ? Auth::user() : null;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if (!$request->user()) {
            return abort(403);
        }
        if ($request->user()->admin === 1) {
            return ['ligazons' => LigazonUsuarioResource::collection(
                LigazonUsuario::with(['etiquetas', 'ligazon'])
                    ->get()
            )];
        }
        return abort(403);
    }

    public function indexConnectedUser(Request $request)
    {
        if (!$this->user) {
            return abort(403);
        }

        return ['ligazons' => LigazonUsuarioResource::collection(
            LigazonUsuario::with('etiquetas', 'ligazon')
                ->where('user_id', $this->user->id)
                ->get()
        )];
    }

    public function indexByUserID(Request $request, User $user)
    {
        if ($request->user()->cannot('view', $user)) {
            return abort(403);
        }
        return LigazonUsuarioResource::collection(LigazonUsuario::with('etiquetas', 'ligazon')->where('user_id', $user->id)->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLigazonUsuarioRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return;
        }

        try {
            $ligazon = Ligazon::firstOrCreate(
                ['url' => $request->url],
                [
                    'titulo' => $request->titulo,
                    'descricion' => $request->descricion,
                    'apropiado' => $request->apropiado,
                    //'visibilidade' => $request->visibilidade,
                ]
            );

            $ligazon->usuarios()->syncWithoutDetaching([
                Auth::id() => [
                    'titulo' => $request->titulo,
                    'descricion' => $request->descricion,
                    'apropiado' => $request->apropiado,
                    'agochado' => $request->agochado,
                ]
            ]);

            $ligazonUsuario = LigazonUsuario::where('user_id', Auth::id())
                ->where('ligazon_id', $ligazon->id)
                ->latest('id')
                ->first();

            if (!$ligazonUsuario) {
                return response()->json([
                    'mensaxe' => 'Houbo un problema ao tentar gardar a ligazón',
                ], 404);
            }

            foreach ($request->etiquetas ?? [] as $tituloEtiqueta) {
                $etiqueta = Etiqueta::firstOrCreate(['titulo' => $tituloEtiqueta]);

                // Asociamos etiqueta con la relación usuario_ligazon
                $ligazonUsuario->etiquetas()->syncWithoutDetaching([
                    $etiqueta->id => [
                        'user_id' => Auth::id(),
                        'apropiado' => $request->apropiado,
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            report($th);
            return abort(409);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(LigazonUsuario $ligazonUsuario)
    {
        if (!$this->user) {
            return abort(403);
        }

        return ['ligazon' => new LigazonUsuarioResource(LigazonUsuario::with(['etiquetas', 'ligazon'])->find($ligazonUsuario->id))];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if (!$this->user) {
            return abort(403);
        }

        if ($request->user()->cannot('update', $user->id)) {
            return abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLigazonUsuarioRequest $request, LigazonUsuario $ligazonUsuario)
    {
        if ($request->user()->cannot('update', $ligazonUsuario)) {
            return abort(403);
        }
        $validator = Validator::make($request->all(), $request->rules());
        if (!$validator) {
            return abort(409);
        }
        $ligazonUsuario->fill($request->validated());
        //$ligazonusuario->etiquetas()->save();
        $ligazonUsuario->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function eliminarLigazons(Request $request)
    {

        // Validación de entrada
        $validator = Validator::make($request->all(), [
            'ligazons' => 'required|array', // Debe ser un array
            'ligazons.*' => 'integer|exists:ligazons,id', // Cada ID debe existir
            'usuario' => 'string',
        ], [
            'ligazons.required' => 'É necesario proporcionar as ligazóns a eliminar.',
            'ligazons.*.exists' => 'Algunhas das ligazóns especificadas non existen.',
        ]);


        // Retornar erros de validación se existen
        if ($validator->fails()) {

            return response()->json([
                'message' => 'Erro de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        //Obtén o usuario conectado por ID
        $user = User::findOrFail(Auth::user() ? Auth::id() : null);

        $ligazons = LigazonUsuario::where('user_id', $user->id)->whereIn('ligazon_id', $request->ligazons)->get();


        try {
            DB::beginTransaction();
            foreach ($ligazons as $ligazonUsuario) {

                if ($request->user()->cannot('delete', $ligazonUsuario)) {
                    return response()->json([
                        'mensaxe' => 'O usuario actual non ten permiso para eliminar esta ligazón.',
                    ], 403);
                }
                $ligazon = $ligazonUsuario->ligazon;
                $ligazonId = $ligazonUsuario->ligazon_id;
                $existeLigazonParaUsuario = null;
                $existeLigazonParaGrupo = null;
                //Para cada ligazón, eliminar as súas etiquetas. Tamén, eliminar as etiquetas que queden orfas.
                $etiquetasLigazon = $ligazonUsuario->etiquetas;


                //Elimina as etiquetas asociadas a esta ligazón, pero só en usuario_ligazon_etiqueta
                $ligazonUsuario->etiquetas()->detach();

                //Elimina a ligazón en usuario_ligazon
                $ligazonUsuario->delete();

                $existeLigazonParaUsuario = LigazonUsuario::where('ligazon_id', $ligazonId)->exists();
                $existeLigazonParaGrupo = LigazonGrupo::where('ligazon_id', $ligazonId)->exists();

                if (!$existeLigazonParaUsuario && !$existeLigazonParaGrupo) {

                    $ligazon->delete();
                }
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
