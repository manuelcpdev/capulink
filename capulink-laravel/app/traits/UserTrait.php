<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

trait UserTrait
{
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
}
