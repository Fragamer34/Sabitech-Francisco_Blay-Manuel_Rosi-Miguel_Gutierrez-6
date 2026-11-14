<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Charla', function (Blueprint $table) {
            $table->increments('idCharla');
            $table->string('nombreCharla', 50);
            $table->text('descripcionCharla');
            $table->date('fechaCharla');
            $table->time('horaInicioCharla');
            $table->time('horaFinCharla');
            $table->enum('estadoCharla', ['PROGRAMADA', 'ACTUALIZADA', 'CANCELADA'])
                  ->default('PROGRAMADA');
            $table->enum('asistenciaCharla', ['HA ASISTIDO', 'NO HA ASISTIDO', 'ASISTENCIA NO REVISADA'])
                  ->default('ASISTENCIA NO REVISADA');
            $table->unsignedInteger('idUsuario');
            $table->unsignedInteger('idSala');

            $table->foreign('idUsuario')
                  ->references('idUsuario')->on('Usuario')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('idSala')
                  ->references('idSala')->on('Sala')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Charla');
    }
};
