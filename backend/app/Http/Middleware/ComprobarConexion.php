<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ComprobarConexion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();
        if(!$usuario) {
            return response()->json([
                'conectado' => false,
            ], 401);
        }
        return response()->json([
            'usuario' => $usuario->name
        ], 200);
        return $next($request);
    }
}
