<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Usuario', function (Blueprint $table) {
            $table->increments('idUsuario');
            $table->string('nombreUsuario', 50);
            $table->string('apellidosUsuario', 200);
            $table->string('emailUsuario', 200)->unique();
            $table->string('contrasenaUsuario_hash', 255);
            $table->date('fechaDeRegistroUsuario')->default(DB::raw('(CURRENT_DATE)'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Usuario');
    }
};
