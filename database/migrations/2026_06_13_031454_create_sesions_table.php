<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id('id_sesiones');
            
            // CORREGIDO: La clase/sesión pertenece a un Grupo Académico específico
            $table->foreignId('id_grupo')->constrained('grupos_academicos', 'id_grupo')->onDelete('restrict');
            $table->foreignId('id_profesor')->constrained('profesores', 'id_profesor')->onDelete('restrict');
            
            $table->dateTime('fecha_sesion');
            $table->text('observacion_sesion')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sesiones');
    }
};