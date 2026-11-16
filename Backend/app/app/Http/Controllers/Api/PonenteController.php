<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ponente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PonenteController extends Controller
{
    /**
     * Listado de ponentes (paginado).
     */
    public function index()
    {
        $ponentes = Ponente::orderBy('apellidosPonente')->paginate(10);

        return response()->json($ponentes);
    }

    /**
     * Crea un ponente.
     */
    public function store(Request $request)
    {
        $ponente = Ponente::create($this->validar($request));

        return response()->json($ponente, 201);
    }

    /**
     * Muestra un ponente.
     */
    public function show(Ponente $ponente)
    {
        return response()->json($ponente);
    }

    /**
     * Actualiza un ponente.
     */
    public function update(Request $request, Ponente $ponente)
    {
        $ponente->update($this->validar($request, $ponente));

        return response()->json($ponente);
    }

    /**
     * Elimina un ponente.
     */
    public function destroy(Ponente $ponente)
    {
        $ponente->delete();

        return response()->noContent();
    }

    /**
     * Reglas de validación compartidas por store y update.
     */
    private function validar(Request $request, ?Ponente $ponente = null): array
    {
        return $request->validate([
            'nombrePonente'       => 'required|string|max:100',
            'apellidosPonente'    => 'required|string|max:150',
            'emailPonente'        => [
                'required', 'email', 'max:150',
                Rule::unique('Ponente', 'emailPonente')->ignore($ponente?->idPonente, 'idPonente'),
            ],
            'especialidadPonente' => 'nullable|string|max:150',
            'biografiaPonente'    => 'nullable|string',
        ]);
    }
}
