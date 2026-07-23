<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inscripcion_cohortes', function (Blueprint $table) {
            $table->id('id_inscripcion_cohortes');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('restrict');
            
            // CORREGIDO: Apunta al Grupo Académico (Salón) en lugar de la Cohorte Global
            $table->foreignId('id_grupo')->constrained('grupos_academicos', 'id_grupo')->onDelete('restrict');
            
            $table->date('fecha_inscripcion');
            $table->string('estatus_inscripcion_cohortes', 50); // Ej. Activo, Retirado, Finalizado
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('inscripcion_cohortes');
    }
};