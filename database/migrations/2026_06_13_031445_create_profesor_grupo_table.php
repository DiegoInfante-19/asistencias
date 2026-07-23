<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profesor_grupo', function (Blueprint $table) {
            $table->id('id_profesor_grupo');
            
            // Llaves foráneas con eliminación en cascada
            $table->foreignId('id_profesor')->constrained('profesores', 'id_profesor')->onDelete('cascade');
            $table->foreignId('id_grupo')->constrained('grupos_academicos', 'id_grupo')->onDelete('cascade');
            
            $table->timestamps();

            // RESTRICCIÓN DE INTEGRIDAD: Un profesor no puede ser asignado dos veces al mismo grupo
            $table->unique(['id_profesor', 'id_grupo'], 'uk_prof_grupo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesor_grupo');
    }
};