<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Charla;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CharlaController extends Controller
{
    /**
     * Catálogo público: charlas programadas, con ponente, sala y plazas libres (paginado).
     */
    public function index(): JsonResponse
    {
        $charlas = Charla::with(['ponente', 'sala'])
            ->conReservasConfirmadas()
            ->where('estadoCharla', 'programada')
            ->orderBy('fechaCharla')
            ->orderBy('horaInicioCharla')
            ->paginate(10);

        return response()->json($charlas->through(fn (Charla $charla) => $this->formato($charla)));
    }

    /**
     * Detalle público de una charla programada, con plazas libres.
     */
    public function show(Charla $charla): JsonResponse
    {
        abort_if($charla->estadoCharla !== 'programada', 404, 'La charla no existe.');

        $charla->load(['ponente', 'sala'])
            ->loadCount(['reservas as reservasConfirmadas' => fn ($r) => $r->where('estadoReserva', 'confirmada')]);

        return response()->json($this->formato($charla));
    }

    /**
     * Crea una charla (solo gestión).
     */
    public function store(Request $request): JsonResponse
    {
        $charla = Charla::create($this->validar($request));

        return response()->json($charla, 201);
    }

    /**
     * Actualiza una charla (solo gestión).
     */
    public function update(Request $request, Charla $charla): JsonResponse
    {
        $charla->update($this->validar($request));

        return response()->json($charla);
    }

    /**
     * Elimina una charla (solo gestión).
     */
    public function destroy(Charla $charla): \Illuminate\Http\Response
    {
        $charla->delete();

        return response()->noContent();
    }

    /**
     * Charla + plazas libres en el mismo formato de respuesta.
     */
    private function formato(Charla $charla): array
    {
        return array_merge($charla->toArray(), ['plazasLibres' => $charla->plazasLibres()]);
    }

    /**
     * Reglas de validación compartidas por store y update (según la migración de Charla).
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'tituloCharla'     => 'required|string|max:200',
            'contenidoCharla'  => 'nullable|string',
            'fechaCharla'      => 'required|date',
            'horaInicioCharla' => 'required|date_format:H:i',
            'horaFinCharla'    => 'required|date_format:H:i|after:horaInicioCharla',
            'aforoCharla'      => 'required|integer|min:1',
            'estadoCharla'     => ['sometimes', Rule::in(['programada', 'cancelada', 'finalizada'])],

            // Claves foráneas
            'idPonente'        => 'required|integer|exists:Ponente,idPonente',
            'idSala'           => 'required|integer|exists:Sala,idSala',
        ]);
    }
}