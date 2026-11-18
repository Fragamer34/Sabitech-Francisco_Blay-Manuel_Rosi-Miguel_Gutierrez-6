<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reserva extends Model
{
    protected $table = 'Reserva';
    protected $primaryKey = 'idReserva';
    public $timestamps = false;

    protected $fillable = [
        'idUsuario',
        'idCharla',
        'fechaReserva',
        'estadoReserva',
        'referenciaReserva',
    ];

    // Mismo valor por defecto que la migración
    protected $attributes = [
        'estadoReserva' => 'confirmada',
    ];

    protected function casts(): array
    {
        return [
            'fechaReserva' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function charla(): BelongsTo
    {
        return $this->belongsTo(Charla::class, 'idCharla', 'idCharla');
    }

    public function asistencia(): HasOne
    {
        return $this->hasOne(Asistencia::class, 'idReserva', 'idReserva');
    }

    public function tokenQr(): HasOne
    {
        return $this->hasOne(TokenQR::class, 'idReserva', 'idReserva');
    }
}
