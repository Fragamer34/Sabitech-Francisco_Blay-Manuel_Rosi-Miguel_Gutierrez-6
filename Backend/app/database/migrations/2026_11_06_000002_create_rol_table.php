
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Rol', function (Blueprint $table) {
            $table->increments('idRol');
            $table->string('nombreRol', 100)->unique();
            $table->string('descripcionRol', 255)->nullable();
        });

        DB::table('Rol')->insert([
            [
                'nombreRol' => 'Administrador general',
                'descripcionRol' => 'Gestiona toda la plataforma y sus usuarios'
            ],
            [
                'nombreRol' => 'Gestor de charlas y reservas',
                'descripcionRol' => 'Gestiona las charlas, salas y reservas'
            ],
            [
                'nombreRol' => 'Gestor de asistencias y notificaciones',
                'descripcionRol' => 'Controla las asistencias y notificaciones'
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('Rol');
    }
};
