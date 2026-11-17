<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TokenAcceso', function (Blueprint $table) {
            $table->increments('idTokenAcceso');

            $table->unsignedInteger('idUsuario');

            $table->string('tokenHash', 64)->unique();

            $table->dateTime('fechaCreacion')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->dateTime('fechaExpiracion');

            $table->foreign('idUsuario')
                ->references('idUsuario')
                ->on('Usuario')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TokenAcceso');
    }
};
