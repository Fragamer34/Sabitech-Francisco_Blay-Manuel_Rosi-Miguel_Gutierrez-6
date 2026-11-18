<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $table = 'Asistencia';
    protected $primaryKey = 'idAsistencia';
    public $timestamps = false;

    protected $fillable = [
        'idReserva',
        'idUsuarioValidador',
        'fechaHoraEntrada',
    ];

    protected function casts(): array
    {
        return [
            'fechaHoraEntrada' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'idReserva', 'idReserva');
    }

    /**
     * Usuario que validó la entrada (puede ser null).
     */
    public function validador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuarioValidador', 'idUsuario');
    }
}
