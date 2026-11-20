<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Tabla intermedia con clave primaria compuesta (idUsuario, idRol).
 */
class UsuarioRolController extends Controller
{
    /**
     * Listado de asignaciones usuario-rol (paginado).
     */
    public function index(Request $request)
    {
        $asignaciones = DB::table('UsuarioRol')
            ->when($request->query('idUsuario'), fn ($q, $id) => $q->where('idUsuario', $id))
            ->when($request->query('idRol'), fn ($q, $id) => $q->where('idRol', $id))
            ->orderBy('idUsuario')
            ->paginate(10);

        return response()->json($asignaciones);
    }

    /**
     * Asigna un rol a un usuario.
     */
    public function store(Request $request)
    {
        $datos = $this->validar($request);

        DB::table('UsuarioRol')->insert($datos);

        return response()->json($datos, 201);
    }

    /**
     * Muestra una asignación concreta.
     */
    public function show(int $idUsuario, int $idRol)
    {
        $asignacion = DB::table('UsuarioRol')
            ->where('idUsuario', $idUsuario)
            ->where('idRol', $idRol)
            ->first();

        abort_if(! $asignacion, 404, 'La asignación no existe.');

        return response()->json($asignacion);
    }

    /**
     * Quita un rol a un usuario.
     */
    public function destroy(int $idUsuario, int $idRol)
    {
        $borradas = DB::table('UsuarioRol')
            ->where('idUsuario', $idUsuario)
            ->where('idRol', $idRol)
            ->delete();

        abort_if($borradas === 0, 404, 'La asignación no existe.');

        return response()->noContent();
    }

    /**
     * Reglas de validación para store.
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'idUsuario' => 'required|integer|exists:Usuario,idUsuario',
            'idRol'     => [
                'required', 'integer', 'exists:Rol,idRol',
                // Evita duplicar la clave primaria compuesta
                Rule::unique('UsuarioRol', 'idRol')->where('idUsuario', $request->input('idUsuario')),
            ],
        ]);
    }
}
