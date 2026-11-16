
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Sala', function (Blueprint $table) {
            $table->increments('idSala');
            $table->string('nombreSala', 100);
            $table->string('ubicacionSala', 150)->nullable();
            $table->integer('capacidadSala');
            $table->text('equipamientoSala')->nullable();
            $table->enum('estadoSala', [
                'disponible',
                'ocupada',
                'mantenimiento'
            ])->default('disponible');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Sala');
    }
};
