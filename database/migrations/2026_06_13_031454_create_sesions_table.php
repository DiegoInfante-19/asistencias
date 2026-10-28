<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id('id_sesiones');
            
            // Foráneas
            $table->foreignId('id_seccion')->constrained('secciones', 'id_seccion')->onDelete('restrict');
            $table->foreignId('id_profesor')->constrained('profesores', 'id_profesor')->onDelete('restrict');
            
            $table->dateTime('fecha_sesion');
            $table->text('observacion_sesion')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // SE ELIMINA EL ÍNDICE UNIQUE GLOBAL: 
            // $table->unique(['id_seccion', 'fecha_sesion'], 'uq_seccion_fecha_sesion');
            // La exclusión de duplicados activos se gestiona de forma segura desde StoreSesionRequest.
        });
    }
    
    public function down(): void {
        Schema::dropIfExists('sesiones');
    }
};