<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificacionController extends Controller
{
    /**
     * Listado de notificaciones (paginado).
     */
    public function index()
    {
        $notificaciones = Notificacion::orderBy('fechaEnvioNotificacion', 'desc')->paginate(10);

        return response()->json($notificaciones);
    }

    /**
     * Crea una notificación.
     */
    public function store(Request $request)
    {
        $notificacion = Notificacion::create($this->validar($request));

        return response()->json($notificacion, 201);
    }

    /**
     * Muestra una notificación.
     */
    public function show(Notificacion $notificacion)
    {
        return response()->json($notificacion);
    }

    /**
     * Actualiza una notificación.
     */
    public function update(Request $request, Notificacion $notificacion)
    {
        $notificacion->update($this->validar($request));

        return response()->json($notificacion);
    }

    /**
     * Elimina una notificación.
     */
    public function destroy(Notificacion $notificacion)
    {
        $notificacion->delete();

        return response()->noContent();
    }

    /**
     * Reglas de validación compartidas por store y update.
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'tituloNotificacion'     => 'required|string|max:150',
            'mensajeNotificacion'    => 'required|string',
            'tipoNotificacion'       => ['sometimes', Rule::in(['reserva', 'recordatorio', 'cancelacion', 'general'])],
            'fechaEnvioNotificacion' => 'sometimes|date',
            'leidaNotificacion'      => 'sometimes|boolean',

            // Clave foránea
            'idUsuario'              => 'required|integer|exists:Usuario,idUsuario',
        ]);
    }
}
