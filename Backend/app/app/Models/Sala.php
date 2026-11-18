<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sala extends Model
{
    protected $table = 'Sala';
    protected $primaryKey = 'idSala';
    public $timestamps = false;

    protected $fillable = [
        'nombreSala',
        'ubicacionSala',
        'capacidadSala',
        'equipamientoSala',
        'estadoSala',
    ];

    // Mismo valor por defecto que la migración
    protected $attributes = [
        'estadoSala' => 'disponible',
    ];

    protected function casts(): array
    {
        return [
            'capacidadSala' => 'integer',
        ];
    }

    public function charlas(): HasMany
    {
        return $this->hasMany(Charla::class, 'idSala', 'idSala');
    }
}
