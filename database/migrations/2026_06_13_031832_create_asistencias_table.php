<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencias');
            $table->foreignId('id_sesiones')->constrained('sesiones', 'id_sesiones')->onDelete('cascade');
            
            // NUEVA FORÁNEA APUNTANDO AL NUEVO NOMBRE DE TABLA INSCRIPCION
            $table->foreignId('id_inscripcion_seccion')->constrained('inscripciones_secciones', 'id_inscripcion_seccion')->onDelete('cascade');
            
            $table->string('estado_asistencia', 20);
            $table->text('observacion_asistencia')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('asistencias');
    }
};