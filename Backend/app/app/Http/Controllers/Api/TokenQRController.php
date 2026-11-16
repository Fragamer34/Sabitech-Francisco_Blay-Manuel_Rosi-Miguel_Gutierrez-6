<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TokenQR;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TokenQRController extends Controller
{
    /**
     * Listado de tokens QR (paginado).
     */
    public function index()
    {
        $tokens = TokenQR::orderBy('fechaCreacionToken', 'desc')->paginate(10);

        return response()->json($tokens);
    }

    /**
     * Crea un token QR.
     */
    public function store(Request $request)
    {
        $token = TokenQR::create($this->validar($request));

        return response()->json($token, 201);
    }

    /**
     * Muestra un token QR.
     */
    public function show(TokenQR $tokenQr)
    {
        return response()->json($tokenQr);
    }

    /**
     * Actualiza un token QR.
     */
    public function update(Request $request, TokenQR $tokenQr)
    {
        $tokenQr->update($this->validar($request, $tokenQr));

        return response()->json($tokenQr);
    }

    /**
     * Elimina un token QR.
     */
    public function destroy(TokenQR $tokenQr)
    {
        $tokenQr->delete();

        return response()->noContent();
    }

    /**
     * Reglas de validación compartidas por store y update.
     */
    private function validar(Request $request, ?TokenQR $tokenQr = null): array
    {
        return $request->validate([
            'tokenQR'              => [
                'required', 'string', 'max:255',
                Rule::unique('TokenQR', 'tokenQR')->ignore($tokenQr?->idToken, 'idToken'),
            ],
            'fechaCreacionToken'   => 'sometimes|date',
            'fechaExpiracionToken' => 'nullable|date',
            'usadoToken'           => 'sometimes|boolean',

            // Clave foránea (un token por reserva)
            'idReserva'            => [
                'required', 'integer', 'exists:Reserva,idReserva',
                Rule::unique('TokenQR', 'idReserva')->ignore($tokenQr?->idToken, 'idToken'),
            ],
        ]);
    }
}
