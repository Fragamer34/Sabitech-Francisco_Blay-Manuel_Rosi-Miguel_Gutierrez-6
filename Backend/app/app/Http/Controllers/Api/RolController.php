<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolController extends Controller
{
    /**
     * Listado de roles (paginado).
     */
    public function index()
    {
        $roles = Rol::orderBy('nombreRol')->paginate(10);

        return response()->json($roles);
    }

    /**
     * Crea un rol.
     */
    public function store(Request $request)
    {
        $rol = Rol::create($this->validar($request));

        return response()->json($rol, 201);
    }

    /**
     * Muestra un rol.
     */
    public function show(Rol $rol)
    {
        return response()->json($rol);
    }

    /**
     * Actualiza un rol.
     */
    public function update(Request $request, Rol $rol)
    {
        $rol->update($this->validar($request, $rol));

        return response()->json($rol);
    }

    /**
     * Elimina un rol.
     */
    public function destroy(Rol $rol)
    {
        $rol->delete();

        return response()->noContent();
    }

    /**
     * Reglas de validación compartidas por store y update.
     */
    private function validar(Request $request, ?Rol $rol = null): array
    {
        return $request->validate([
            'nombreRol'      => [
                'required', 'string', 'max:100',
                Rule::unique('Rol', 'nombreRol')->ignore($rol?->idRol, 'idRol'),
            ],
            'descripcionRol' => 'nullable|string|max:255',
        ]);
    }
}
