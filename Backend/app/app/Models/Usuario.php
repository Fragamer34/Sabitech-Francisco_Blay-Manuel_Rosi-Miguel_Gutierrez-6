<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    protected $table = 'Usuario';
    protected $primaryKey = 'idUsuario';
    public $timestamps = false;

    protected $fillable = [
        'nombreUsuario',
        'apellidosUsuario',
        'emailUsuario',
        'contrasenaUsuario_hash',
        'fechaDeRegistroUsuario',
    ];

    protected $hidden = [
        'contrasenaUsuario_hash',
    ];

    protected function casts(): array
    {
        return [
            'fechaDeRegistroUsuario' => 'date:Y-m-d',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'UsuarioRol', 'idUsuario', 'idRol');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'idUsuario', 'idUsuario');
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'idUsuario', 'idUsuario');
    }

    public function tokensAcceso(): HasMany
    {
        return $this->hasMany(TokenAcceso::class, 'idUsuario', 'idUsuario');
    }

    /**
     * Asistencias que este usuario ha validado (como validador).
     */
    public function asistenciasValidadas(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'idUsuarioValidador', 'idUsuario');
    }

    /**
     * Indica si el usuario tiene alguno de los roles (por nombreRol).
     */
    public function tieneRol(string ...$nombres): bool
    {
        return $this->roles()->whereIn('nombreRol', $nombres)->exists();
    }
}