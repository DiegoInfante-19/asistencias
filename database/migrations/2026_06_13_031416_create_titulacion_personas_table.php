<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('titulacion_personas', function (Blueprint $table) {
            $table->id('id_titulacion_personas');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('cascade');
            $table->foreignId('id_titulacion')->constrained('titulos', 'id_titulos')->onDelete('restrict');
            $table->foreignId('id_pnf')->constrained('pnfs', 'id_pnf')->onDelete('restrict');
            $table->foreignId('id_estatus_expediente')->constrained('estatus_expedientes', 'id_estatus_expediente')->onDelete('restrict');
        });
    }

    public function down(): void {
        Schema::dropIfExists('titulacion_personas');
    }
};