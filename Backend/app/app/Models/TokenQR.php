<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenQR extends Model
{
    protected $table = 'TokenQR';
    protected $primaryKey = 'idToken';
    public $timestamps = false;

    protected $fillable = [
        'idReserva',
        'tokenQR',
        'fechaCreacionToken',
        'fechaExpiracionToken',
        'usadoToken',
    ];

    // Mismo valor por defecto que la migración
    protected $attributes = [
        'usadoToken' => false,
    ];

    protected function casts(): array
    {
        return [
            'fechaCreacionToken'   => 'datetime:Y-m-d H:i:s',
            'fechaExpiracionToken' => 'datetime:Y-m-d H:i:s',
            'usadoToken'           => 'boolean',
        ];
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'idReserva', 'idReserva');
    }
}
