
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Ponente', function (Blueprint $table) {
            $table->increments('idPonente');
            $table->string('nombrePonente', 100);
            $table->string('apellidosPonente', 150);
            $table->string('emailPonente', 150)->unique();
            $table->string('especialidadPonente', 150)->nullable();
            $table->text('biografiaPonente')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Ponente');
    }
};
