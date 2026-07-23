<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos_academicos', function (Blueprint $table) {
            $table->id('id_grupo');
            
            // Relaciones
            $table->foreignId('id_cohortes')->constrained('cohortes', 'id_cohortes')->onDelete('cascade');
            $table->foreignId('id_pnf')->constrained('pnfs', 'id_pnf')->onDelete('cascade');
            
            // Nivel Académico tipado desde el Enum de PHP
            $table->string('nivel_academico', 20);
            $table->string('estatus_grupo', 50)->default('Activo');
            
            $table->timestamps();

            // RESTRICCIÓN DE INTEGRIDAD: No pueden existir dos grupos idénticos en la misma cohorte
            $table->unique(['id_cohortes', 'id_pnf', 'nivel_academico'], 'uk_grupo_unico');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos_academicos');
    }
};