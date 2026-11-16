<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sala;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalaController extends Controller
{
    /**
     * Listado de salas (paginado).
     */
    public function index()
    {
        $salas = Sala::orderBy('nombreSala')->paginate(10);

        return response()->json($salas);
    }

    /**
     * Crea una sala.
     */
    public function store(Request $request)
    {
        $sala = Sala::create($this->validar($request));

        return response()->json($sala, 201);
    }

    /**
     * Muestra una sala.
     */
    public function show(Sala $sala)
    {
        return response()->json($sala);
    }

    /**
     * Actualiza una sala.
     */
    public function update(Request $request, Sala $sala)
    {
        $sala->update($this->validar($request));

        return response()->json($sala);
    }

    /**
     * Elimina una sala.
     */
    public function destroy(Sala $sala)
    {
        $sala->delete();

        return response()->noContent();
    }

    /**
     * Reglas de validación compartidas por store y update.
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'nombreSala'       => 'required|string|max:100',
            'ubicacionSala'    => 'nullable|string|max:150',
            'capacidadSala'    => 'required|integer|min:1',
            'equipamientoSala' => 'nullable|string',
            'estadoSala'       => ['sometimes', Rule::in(['disponible', 'ocupada', 'mantenimiento'])],
        ]);
    }
}
