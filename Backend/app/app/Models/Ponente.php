<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ponente extends Model
{
    protected $table = 'Ponente';
    protected $primaryKey = 'idPonente';
    public $timestamps = false;

    protected $fillable = [
        'nombrePonente',
        'apellidosPonente',
        'emailPonente',
        'especialidadPonente',
        'biografiaPonente',
    ];

    public function charlas(): HasMany
    {
        return $this->hasMany(Charla::class, 'idPonente', 'idPonente');
    }
}
