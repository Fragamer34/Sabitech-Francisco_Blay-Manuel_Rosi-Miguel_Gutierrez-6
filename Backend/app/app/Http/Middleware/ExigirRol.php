<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Permite el paso solo a usuarios que tengan alguno de los roles indicados.
 * Uso en rutas: ->middleware('rol:Administrador general,Gestor de charlas y reservas')
 */
class ExigirRol
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        if (! $usuario) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        if (! $usuario->tieneRol(...$roles)) {
            return response()->json(['message' => 'No tienes permiso para realizar esta acción.'], 403);
        }

        return $next($request);
    }
}
