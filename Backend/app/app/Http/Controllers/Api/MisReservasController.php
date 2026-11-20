<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Charla;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Reservas y asistencia de un usuario. No usa tokens: el frontend envía el idUsuario
 * del usuario que ha iniciado sesión y aquí solo se comprueba que ese usuario existe.
 */
class MisReservasController extends Controller
{
    /**
     * Reservas confirmadas del usuario, indicando si ya ha confirmado su asistencia.
     * Uso: GET /api/mis-reservas?idUsuario=1
     */
    public function index(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'idUsuario' => ['required', 'integer', 'exists:Usuario,idUsuario'],
        ]);

        $reservas = Reserva::with('asistencia')
            ->where('idUsuario', $datos['idUsuario'])
            ->where('estadoReserva', 'confirmada')
            ->orderByDesc('fechaReserva')
            ->get()
            ->map(fn (Reserva $reserva) => [
                'idReserva'            => $reserva->idReserva,
                'idCharla'             => $reserva->idCharla,
                'referenciaReserva'    => $reserva->referenciaReserva,
                'fechaReserva'         => $reserva->fechaReserva?->format('Y-m-d H:i:s'),
                'asistenciaConfirmada' => $reserva->asistencia !== null,
            ]);

        return response()->json($reservas);
    }

    /**
     * Reserva una charla para el usuario.
     * Uso: POST /api/mis-reservas  { idUsuario, idCharla }
     */
    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'idUsuario' => ['required', 'integer', 'exists:Usuario,idUsuario'],
            'idCharla'  => ['required', 'integer', 'exists:Charla,idCharla'],
        ]);

        $idUsuario = (int) $datos['idUsuario'];

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

    /**
     * Confirma la asistencia del usuario a una charla que ha reservado (guarda una fila en Asistencia).
     * Uso: POST /api/mis-reservas/{idReserva}/asistencia  { idUsuario }
     */
    public function confirmarAsistencia(Request $request, Reserva $reserva): JsonResponse
    {
        $datos = $request->validate([
            'idUsuario' => ['required', 'integer', 'exists:Usuario,idUsuario'],
        ]);

        if ((int) $reserva->idUsuario !== (int) $datos['idUsuario']) {
            return response()->json(['message' => 'Esta reserva no es tuya.'], 403);
        }

        if ($reserva->estadoReserva !== 'confirmada') {
            return response()->json(['message' => 'La reserva no está confirmada.'], 422);
        }

        // Una reserva solo puede tener una asistencia: si ya existe, se devuelve la misma
        $asistencia = Asistencia::firstOrCreate(
            ['idReserva' => $reserva->idReserva],
            ['fechaHoraEntrada' => now()],
        );

        return response()->json($asistencia, $asistencia->wasRecentlyCreated ? 201 : 200);
    }

    private function generarReferencia(): string
    {
        do {
            $referencia = 'RES-'.strtoupper(Str::random(8));
        } while (Reserva::where('referenciaReserva', $referencia)->exists());

        return $referencia;
    }
}
