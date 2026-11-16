<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservaController extends Controller
{
    /**
     * Listado de reservas (paginado).
     */
    public function index()
    {
        $reservas = Reserva::orderBy('fechaReserva', 'desc')->paginate(10);

        return response()->json($reservas);
    }

    /**
     * Crea una reserva.
     */
    public function store(Request $request)
    {
        $reserva = Reserva::create($this->validar($request));

        return response()->json($reserva, 201);
    }

    /**
     * Muestra una reserva.
     */
    public function show(Reserva $reserva)
    {
        return response()->json($reserva);
    }

    /**
     * Actualiza una reserva.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $reserva->update($this->validar($request, $reserva));

        return response()->json($reserva);
    }

    /**
     * Elimina una reserva.
     */
    public function destroy(Reserva $reserva)
    {
        $reserva->delete();

        return response()->noContent();
    }

    /**
     * Reglas de validación compartidas por store y update.
     */
    private function validar(Request $request, ?Reserva $reserva = null): array
    {
        return $request->validate([
            'fechaReserva'      => 'sometimes|date',
            'estadoReserva'     => ['sometimes', Rule::in(['confirmada', 'cancelada'])],
            'referenciaReserva' => [
                'required', 'string', 'max:100',
                Rule::unique('Reserva', 'referenciaReserva')->ignore($reserva?->idReserva, 'idReserva'),
            ],

            // Claves foráneas
            'idUsuario'         => 'required|integer|exists:Usuario,idUsuario',
            'idCharla'          => [
                'required', 'integer', 'exists:Charla,idCharla',
                // Un usuario solo puede reservar una vez la misma charla
                Rule::unique('Reserva', 'idCharla')
                    ->where('idUsuario', $request->input('idUsuario'))
                    ->ignore($reserva?->idReserva, 'idReserva'),
            ],
        ]);
    }
}
