
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Notificacion', function (Blueprint $table) {
            $table->increments('idNotificacion');

            $table->unsignedInteger('idUsuario');

            $table->string('tituloNotificacion', 150);
            $table->text('mensajeNotificacion');

            $table->enum('tipoNotificacion', [
                'reserva',
                'recordatorio',
                'cancelacion',
                'general'
            ])->default('general');

            $table->dateTime('fechaEnvioNotificacion')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->boolean('leidaNotificacion')->default(false);

            $table->foreign('idUsuario')
                ->references('idUsuario')
                ->on('Usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Notificacion');
    }
};
