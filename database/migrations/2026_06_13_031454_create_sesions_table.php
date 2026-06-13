<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sesiones', function (Blueprint $table) {
            $table->id('id_sesiones');
            $table->foreignId('id_cohortes')->constrained('cohortes', 'id_cohortes')->onDelete('restrict');
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