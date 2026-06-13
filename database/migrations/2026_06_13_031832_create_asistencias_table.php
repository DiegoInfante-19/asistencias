<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencias');
            $table->foreignId('id_sesiones')->constrained('sesiones', 'id_sesiones')->onDelete('cascade');
            $table->foreignId('id_inscripcion_cohortes')->constrained('inscripcion_cohortes', 'id_inscripcion_cohortes')->onDelete('cascade');
            $table->enum('estado_asistencia', ['presente', 'ausente', 'justificada']);
            $table->text('observacion_asistencia')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asistencias');
    }
};