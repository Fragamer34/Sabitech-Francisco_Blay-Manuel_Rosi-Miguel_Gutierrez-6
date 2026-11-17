<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TokenAcceso;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Registro de un nuevo asistente. Devuelve también un token.
     */
    public function registro(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'nombreUsuario'     => 'required|string|max:50',
            'apellidosUsuario'  => 'required|string|max:200',
            'emailUsuario'      => 'required|email|max:200|unique:Usuario,emailUsuario',
            'contrasenaUsuario' => 'required|string|min:8|max:255|confirmed',
        ]);

        $usuario = Usuario::create([
            'nombreUsuario'          => $datos['nombreUsuario'],
            'apellidosUsuario'       => $datos['apellidosUsuario'],
            'emailUsuario'           => $datos['emailUsuario'],
            'contrasenaUsuario_hash' => Hash::make($datos['contrasenaUsuario']),
            'fechaDeRegistroUsuario' => now()->toDateString(),
        ]);

        return $this->respuestaConToken($usuario, 201);
    }

    /**
     * Inicio de sesión: devuelve un token Bearer.
     */
    public function login(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'emailUsuario'      => 'required|email',
            'contrasenaUsuario' => 'required|string',
        ]);

        $usuario = Usuario::where('emailUsuario', $datos['emailUsuario'])->first();

        // Mismo mensaje si el email no existe o la contraseña no coincide
        if (! $usuario || ! Hash::check($datos['contrasenaUsuario'], $usuario->contrasenaUsuario_hash)) {
            return response()->json(['message' => 'Email o contraseña incorrectos.'], 401);
        }

        return $this->respuestaConToken($usuario, 200);
    }

    /**
     * Cierra la sesión invalidando el token usado en la petición.
     */
    public function logout(Request $request): \Illuminate\Http\Response
    {
        $request->attributes->get('tokenAcceso')->delete();

        return response()->noContent();
    }

    /**
     * Datos del usuario identificado, con sus roles.
     */
    public function me(Request $request): JsonResponse
    {
        $usuario = $request->user()->load('roles:idRol,nombreRol,descripcionRol');

        return response()->json($usuario);
    }

    private function respuestaConToken(Usuario $usuario, int $codigo): JsonResponse
    {
        $token = TokenAcceso::emitir($usuario);

        return response()->json([
            'token'   => $token['texto'],
            'tipo'    => 'Bearer',
            'expira'  => $token['expira'],
            'usuario' => $usuario,
        ], $codigo);
    }
}
