<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Registro de un nuevo asistente: guarda el usuario en la base de datos.
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

        return response()->json(['usuario' => $usuario], 201);
    }

    /**
     * Inicio de sesión: comprueba email y contraseña y devuelve los datos del usuario.
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

        return response()->json(['usuario' => $usuario]);
    }
}
