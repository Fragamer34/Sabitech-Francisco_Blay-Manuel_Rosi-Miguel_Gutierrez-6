<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AsistenciaController extends Controller
{
    /**
     * Listado de asistencias (paginado).
     */
    public function index()
    {
        $asistencias = Asistencia::orderBy('fechaHoraEntrada', 'desc')->paginate(10);

        return response()->json($asistencias);
    }

    /**
     * Crea una asistencia.
     */
    public function store(Request $request)
    {
        $asistencia = Asistencia::create($this->validar($request));

        return response()->json($asistencia, 201);
    }

    /**
     * Muestra una asistencia.
     */
    public function show(Asistencia $asistencia)
    {
        return response()->json($asistencia);
    }

    /**
     * Actualiza una asistencia.
     */
    public function update(Request $request, Asistencia $asistencia)
    {
        $asistencia->update($this->validar($request, $asistencia));

        return response()->json($asistencia);
    }

    /**
     * Elimina una asistencia.
     */
    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();

        return response()->noContent();
    }

    /**
     * Reglas de validación compartidas por store y update.
     */
    private function validar(Request $request, ?Asistencia $asistencia = null): array
    {
        return $request->validate([
            'fechaHoraEntrada'   => 'sometimes|date',

            // Claves foráneas
            'idReserva'          => [
                'required', 'integer', 'exists:Reserva,idReserva',
                // Cada reserva solo puede tener una asistencia
                Rule::unique('Asistencia', 'idReserva')->ignore($asistencia?->idAsistencia, 'idAsistencia'),
            ],
            'idUsuarioValidador' => 'nullable|integer|exists:Usuario,idUsuario',
        ]);
    }
}
