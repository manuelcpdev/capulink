<?php

namespace App\Http\Middleware;

use App\Models\Ligazon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PodeEditarLigazonUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::find(Auth::user()->id);

        $ligazon = $user->ligazons()->where('ligazon_id', $request->route('id'))->first();

        if(!$ligazon) {
            return response()->json([
                'error' => 'Non se atopou a ligazón para este usuario.',
            ], 403);
        }

        return $next($request);
    }
}
