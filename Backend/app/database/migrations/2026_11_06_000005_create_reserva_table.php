<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Reserva', function (Blueprint $table) {
            $table->increments('idReservas');
            $table->unsignedInteger('idUsuario');
            $table->unsignedInteger('idCharla');
            $table->dateTime('fechaReserva')->useCurrent();
            $table->enum('estadoReserva', ['ACTIVA', 'ANULADA'])->default('ACTIVA');

            $table->foreign('idUsuario')
                  ->references('idUsuario')->on('Usuario')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('idCharla')
                  ->references('idCharla')->on('Charla')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Reserva');
    }
};
