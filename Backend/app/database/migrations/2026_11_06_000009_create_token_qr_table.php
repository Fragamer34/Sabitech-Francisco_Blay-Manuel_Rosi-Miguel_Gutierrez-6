
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TokenQR', function (Blueprint $table) {
            $table->increments('idToken');

            $table->unsignedInteger('idReserva');

            $table->string('tokenQR', 255)->unique();

            $table->dateTime('fechaCreacionToken')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->dateTime('fechaExpiracionToken')->nullable();

            $table->boolean('usadoToken')->default(false);

            $table->unique('idReserva');

            $table->foreign('idReserva')
                ->references('idReserva')
                ->on('Reserva');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TokenQR');
    }
};
