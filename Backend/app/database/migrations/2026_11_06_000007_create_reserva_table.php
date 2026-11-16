
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Reserva', function (Blueprint $table) {
            $table->increments('idReserva');

            $table->unsignedInteger('idUsuario');
            $table->unsignedInteger('idCharla');

            $table->dateTime('fechaReserva')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->enum('estadoReserva', [
                'confirmada',
                'cancelada'
            ])->default('confirmada');

            $table->string('referenciaReserva', 100)->unique();

            $table->unique(['idUsuario', 'idCharla']);

            $table->foreign('idUsuario')
                ->references('idUsuario')
                ->on('Usuario');

            $table->foreign('idCharla')
                ->references('idCharla')
                ->on('Charla');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Reserva');
    }
};
