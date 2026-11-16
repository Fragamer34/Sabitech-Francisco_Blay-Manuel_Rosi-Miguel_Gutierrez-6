
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
            $table->string('tituloCharla', 200);
            $table->text('contenidoCharla')->nullable();

            $table->unsignedInteger('idPonente');
            $table->unsignedInteger('idSala');

            $table->date('fechaCharla');
            $table->time('horaInicioCharla');
            $table->time('horaFinCharla');

            $table->integer('aforoCharla');

            $table->enum('estadoCharla', [
                'programada',
                'cancelada',
                'finalizada'
            ])->default('programada');

            $table->foreign('idPonente')
                ->references('idPonente')
                ->on('Ponente');

            $table->foreign('idSala')
                ->references('idSala')
                ->on('Sala');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Charla');
    }
};
