<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Token de acceso de la API. En la base de datos se guarda solo el hash SHA-256;
 * el texto del token se devuelve una única vez, en el login o el registro.
 */
class TokenAcceso extends Model
{
    protected $table = 'TokenAcceso';
    protected $primaryKey = 'idTokenAcceso';
    public $timestamps = false;

    public const DURACION_HORAS = 8;

    protected $fillable = [
        'idUsuario',
        'tokenHash',
        'fechaCreacion',
        'fechaExpiracion',
    ];

    protected function casts(): array
    {
        return [
            'fechaCreacion' => 'datetime',
            'fechaExpiracion' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Crea un token nuevo para el usuario y devuelve su texto (solo se muestra una vez).
     */
    public static function emitir(Usuario $usuario): array
    {
        $texto = bin2hex(random_bytes(32));

        $token = self::create([
            'idUsuario' => $usuario->idUsuario,
            'tokenHash' => hash('sha256', $texto),
            'fechaCreacion' => now(),
            'fechaExpiracion' => now()->addHours(self::DURACION_HORAS),
        ]);

        return ['texto' => $texto, 'expira' => $token->fechaExpiracion];
    }

    /**
     * Busca un token vigente a partir de su texto. Devuelve null si no existe o ha caducado.
     */
    public static function validoPara(string $texto): ?self
    {
        return self::with('usuario')
            ->where('tokenHash', hash('sha256', $texto))
            ->where('fechaExpiracion', '>', now())
            ->first();
    }
}
