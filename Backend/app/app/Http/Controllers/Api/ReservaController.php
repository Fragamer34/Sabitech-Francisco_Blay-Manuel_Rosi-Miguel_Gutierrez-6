<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Charla;
use App\Models\Reserva;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Reservas del usuario identificado.
 */
class MisReservasController extends Controller
{
    /**
     * Listado de mis reservas (paginado).
     */
    public function index(Request $request): JsonResponse
    {
        $reservas = Reserva::with(['charla.sala', 'charla.ponente'])
            ->where('idUsuario', $request->user()->idUsuario)
            ->orderByDesc('fechaReserva')
            ->paginate(10);

        return response()->json($reservas);
    }

    /**
     * Reserva una charla para el usuario identificado.
     */
    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'idCharla' => ['required', 'integer', 'exists:Charla,idCharla'],
        ]);

        $idUsuario = $request->user()->idUsuario;

        return DB::transaction(function () use ($idUsuario, $datos) {
            // Bloquea la fila de la charla: dos reservas simultáneas no pueden superar el aforo
            $charla = Charla::whereKey($datos['idCharla'])->lockForUpdate()->firstOrFail();

            if ($charla->estadoCharla !== 'programada') {
                return response()->json(['message' => 'Esta charla no admite reservas.'], 422);
            }

            if ($charla->fechaCharla->lt(Carbon::today())) {
                return response()->json(['message' => 'La charla ya ha pasado.'], 422);
            }

            $yaReservada = Reserva::where('idUsuario', $idUsuario)
                ->where('idCharla', $charla->idCharla)
                ->exists();

            if ($yaReservada) {
                return response()->json(['message' => 'Ya tienes una reserva para esta charla.'], 409);
            }

            $ocupadas = Reserva::where('idCharla', $charla->idCharla)
                ->where('estadoReserva', 'confirmada')
                ->count();

            if ($ocupadas >= $charla->aforoCharla) {
                return response()->json(['message' => 'No quedan plazas libres para esta charla.'], 409);
            }

            $reserva = Reserva::create([
                'idUsuario'         => $idUsuario,
                'idCharla'          => $charla->idCharla,
                'fechaReserva'      => now(),
                'estadoReserva'     => 'confirmada',
                'referenciaReserva' => $this->generarReferencia(),
            ]);

            return response()->json($reserva->load(['charla.sala', 'charla.ponente']), 201);
        });
    }

    private function generarReferencia(): string
    {
        do {
            $referencia = 'RES-'.strtoupper(Str::random(8));
        } while (Reserva::where('referenciaReserva', $referencia)->exists());

        return $referencia;
    }
}
