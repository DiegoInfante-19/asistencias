<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titulos', function (Blueprint $table) {
            $table->id('id_titulos');
            $table->string('nombre_titulo_base', 100); // Ej. TSU, INGENIERIA
            $table->string('nivel_academico', 50);     // Ej. media, universitaria
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titulos');
    }
};