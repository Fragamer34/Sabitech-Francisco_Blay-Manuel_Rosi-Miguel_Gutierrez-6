<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'Notificacion';
    protected $primaryKey = 'idNotificacion';
    public $timestamps = false;

    protected $fillable = [
        'idUsuario',
        'tituloNotificacion',
        'mensajeNotificacion',
        'tipoNotificacion',
        'fechaEnvioNotificacion',
        'leidaNotificacion',
    ];

    // Mismos valores por defecto que la migración
    protected $attributes = [
        'tipoNotificacion'  => 'general',
        'leidaNotificacion' => false,
    ];

    protected function casts(): array
    {
        return [
            'fechaEnvioNotificacion' => 'datetime:Y-m-d H:i:s',
            'leidaNotificacion'      => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}
