<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscripciones_secciones', function (Blueprint $table) {
            $table->id('id_inscripcion_seccion');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('restrict');
            $table->foreignId('id_seccion')->constrained('secciones', 'id_seccion')->onDelete('restrict');

            $table->date('fecha_inscripcion');
            $table->string('estatus_inscripcion', 50);

            // ÍNDICES EXPLÍCITOS PARA BÚSQUEDAS RÁPIDAS DE ASISTENCIA Y MATRÍCULA
            $table->index('id_personas');
            $table->index('id_seccion');
            $table->index('estatus_inscripcion');

            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inscripciones_secciones');
    }
};
