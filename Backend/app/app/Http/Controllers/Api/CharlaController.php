<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Charla;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CharlaController extends Controller
{
    /**
     * Listado de charlas (paginado).
     */
    public function index()
    {
        $charlas = Charla::orderBy('fechaCharla', 'desc')->paginate(10);

        return response()->json($charlas);
    }

    /**
     * Crea una charla.
     */
    public function store(Request $request)
    {
        $charla = Charla::create($this->validar($request));

        return response()->json($charla, 201);
    }

    /**
     * Muestra una charla.
     */
    public function show(Charla $charla)
    {
        return response()->json($charla);
    }

    /**
     * Actualiza una charla.
     */
    public function update(Request $request, Charla $charla)
    {
        $charla->update($this->validar($request));

        return response()->json($charla);
    }

    /**
     * Elimina una charla.
     */
    public function destroy(Charla $charla)
    {
        $charla->delete();

        return response()->noContent();
    }

    /**
     * Reglas de validación compartidas por store y update.
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'nombreCharla'      => 'required|string|max:50',
            'descripcionCharla' => 'required|string',
            'fechaCharla'       => 'required|date',
            'horaInicioCharla'  => 'required|date_format:H:i',
            'horaFinCharla'     => 'required|date_format:H:i|after:horaInicioCharla',
            'estadoCharla'      => ['sometimes', Rule::in(['PROGRAMADA', 'ACTUALIZADA', 'CANCELADA'])],
            'asistenciaCharla'  => ['sometimes', Rule::in(['HA ASISTIDO', 'NO HA ASISTIDO', 'ASISTENCIA NO REVISADA'])],

            // Claves foráneas
            'idUsuario'         => 'required|integer|exists:Usuario,idUsuario',
            'idSala'            => 'required|integer|exists:Sala,idSala',
        ]);
    }
}