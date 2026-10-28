<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('titulacion_personas', function (Blueprint $table) {
            $table->id('id_titulacion_personas');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('cascade');
            
            // Campos de titulación y PNF
            $table->foreignId('id_titulacion')->nullable()->constrained('titulos', 'id_titulos')->nullOnDelete();
            $table->foreignId('id_titulos_pnf')->nullable()->constrained('titulos_pnf', 'id_titulos_pnf')->nullOnDelete();
            $table->foreignId('id_pnf')->constrained('pnfs', 'id_pnf')->onDelete('restrict');
            $table->foreignId('id_estatus_expediente')->nullable()->constrained('estatus_expedientes', 'id_estatus_expediente')->nullOnDelete();

            // Índices para optimizar consultas de DataTables
            $table->index('id_personas');
            $table->index('id_pnf');
            $table->index('id_titulos_pnf');
            $table->index('id_estatus_expediente');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titulacion_personas');
    }
};