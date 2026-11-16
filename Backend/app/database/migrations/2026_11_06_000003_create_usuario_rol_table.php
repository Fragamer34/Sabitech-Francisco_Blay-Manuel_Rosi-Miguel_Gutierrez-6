
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('UsuarioRol', function (Blueprint $table) {
            $table->unsignedInteger('idUsuario');
            $table->unsignedInteger('idRol');

            $table->primary(['idUsuario', 'idRol']);

            $table->foreign('idUsuario')
                ->references('idUsuario')
                ->on('Usuario');

            $table->foreign('idRol')
                ->references('idRol')
                ->on('Rol');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('UsuarioRol');
    }
};
