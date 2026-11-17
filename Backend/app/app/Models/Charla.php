<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Añade el número de reservas confirmadas (alias reservasConfirmadas) para calcular plazas.
     */
    public function scopeConReservasConfirmadas(Builder $query): Builder
    {
        return $query->withCount([
            'reservas as reservasConfirmadas' => fn (Builder $r) => $r->where('estadoReserva', 'confirmada'),
        ]);
    }

    /**
     * Plazas libres = aforo − reservas confirmadas (nunca negativo).
     */
    public function plazasLibres(): int
    {
        $ocupadas = $this->reservasConfirmadas
            ?? $this->reservas()->where('estadoReserva', 'confirmada')->count();

        return max(0, $this->aforoCharla - (int) $ocupadas);
    }
}