<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id('id_sesiones');
            
            // NUEVA FORÁNEA (Sustituye a grupo)
            $table->foreignId('id_seccion')->constrained('secciones', 'id_seccion')->onDelete('restrict');
            
            $table->foreignId('id_profesor')->constrained('profesores', 'id_profesor')->onDelete('restrict');
            $table->dateTime('fecha_sesion');
            $table->text('observacion_sesion')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // Nuevo índice único para secciones
            $table->unique(['id_seccion', 'fecha_sesion'], 'uq_seccion_fecha_sesion');
        });
    }
    public function down(): void {
        Schema::dropIfExists('sesiones');
    }
};