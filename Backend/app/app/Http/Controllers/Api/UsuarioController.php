<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios (paginado).
     */
    public function index()
    {
        $usuarios = Usuario::orderBy('apellidosUsuario')->paginate(10);

        $usuarios->getCollection()->each->makeHidden('contrasenaUsuario_hash');

        return response()->json($usuarios);
    }

    /**
     * Crea un usuario.
     */
    public function store(Request $request)
    {
        $datos = $this->validar($request);

        $datos['contrasenaUsuario_hash'] = Hash::make($datos['contrasenaUsuario']);
        unset($datos['contrasenaUsuario']);

        $usuario = Usuario::create($datos);

        return response()->json($usuario->makeHidden('contrasenaUsuario_hash'), 201);
    }

    /**
     * Muestra un usuario.
     */
    public function show(Usuario $usuario)
    {
        return response()->json($usuario->makeHidden('contrasenaUsuario_hash'));
    }

    /**
     * Actualiza un usuario.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $datos = $this->validar($request, $usuario);

        if (isset($datos['contrasenaUsuario'])) {
            $datos['contrasenaUsuario_hash'] = Hash::make($datos['contrasenaUsuario']);
        }
        unset($datos['contrasenaUsuario']);

        $usuario->update($datos);

        return response()->json($usuario->makeHidden('contrasenaUsuario_hash'));
    }

    /**
     * Elimina un usuario.
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return response()->noContent();
    }

    /**
     * Reglas de validación compartidas por store y update.
     */
    private function validar(Request $request, ?Usuario $usuario = null): array
    {
        return $request->validate([
            'nombreUsuario'          => 'required|string|max:50',
            'apellidosUsuario'       => 'required|string|max:200',
            'emailUsuario'           => [
                'required', 'email', 'max:200',
                Rule::unique('Usuario', 'emailUsuario')->ignore($usuario?->idUsuario, 'idUsuario'),
            ],
            // Contraseña en claro: se guarda hasheada en contrasenaUsuario_hash
            'contrasenaUsuario'      => [$usuario ? 'sometimes' : 'required', 'string', 'min:8', 'max:255'],
            'fechaDeRegistroUsuario' => 'sometimes|date',
        ]);
    }
}
