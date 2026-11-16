
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Asistencia', function (Blueprint $table) {
            $table->increments('idAsistencia');

            $table->unsignedInteger('idReserva');
            $table->unsignedInteger('idUsuarioValidador')->nullable();

            $table->dateTime('fechaHoraEntrada')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->unique('idReserva');

            $table->foreign('idReserva')
                ->references('idReserva')
                ->on('Reserva');

            $table->foreign('idUsuarioValidador')
                ->references('idUsuario')
                ->on('Usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Asistencia');
    }
};
