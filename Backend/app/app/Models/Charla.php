<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Charla extends Model
{
    protected $table = 'Charla';
    protected $primaryKey = 'idCharla';
    public $timestamps = false;

    protected $fillable = [
        'tituloCharla',
        'contenidoCharla',
        'idPonente',
        'idSala',
        'fechaCharla',
        'horaInicioCharla',
        'horaFinCharla',
        'aforoCharla',
        'estadoCharla',
    ];

    // Mismo valor por defecto que la migración
    protected $attributes = [
        'estadoCharla' => 'programada',
    ];

    protected function casts(): array
    {
        return [
            'fechaCharla' => 'date:Y-m-d',
            'aforoCharla' => 'integer',
        ];
    }

    public function ponente(): BelongsTo
    {
        return $this->belongsTo(Ponente::class, 'idPonente', 'idPonente');
    }

    public function sala(): BelongsTo
    {
        return $this->belongsTo(Sala::class, 'idSala', 'idSala');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'idCharla', 'idCharla');
    }
}
