<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    protected $table = 'Rol';
    protected $primaryKey = 'idRol';
    public $timestamps = false;

    protected $fillable = [
        'nombreRol',
        'descripcionRol',
    ];

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'UsuarioRol', 'idRol', 'idUsuario');
    }
}
