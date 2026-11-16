<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Rol', function (Blueprint $table) {
            $table->increments('idRoles');
            $table->enum('tipoRol', ['ADMIN', 'ASISTENTE', 'INVITADO', 'PONENTE'])
                  ->default('ASISTENTE');
            $table->unsignedInteger('idUsuario');

            $table->foreign('idUsuario')
                  ->references('idUsuario')->on('Usuario')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Rol');
    }
};
