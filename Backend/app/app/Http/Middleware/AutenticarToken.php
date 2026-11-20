<?php

namespace App\Http\Middleware;

use App\Models\TokenAcceso;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Identifica al usuario a partir de la cabecera "Authorization: Bearer <token>".
 */
class AutenticarToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $textoToken = $request->bearerToken();

        if (! $textoToken) {
            return response()->json(['message' => 'No autenticado. Envía la cabecera Authorization: Bearer <token>.'], 401);
        }

        $token = TokenAcceso::validoPara($textoToken);

        if (! $token) {
            return response()->json(['message' => 'Token no válido o caducado.'], 401);
        }

        $usuario = $token->usuario;
        $request->setUserResolver(fn () => $usuario);
        $request->attributes->set('tokenAcceso', $token);

        return $next($request);
    }
}
