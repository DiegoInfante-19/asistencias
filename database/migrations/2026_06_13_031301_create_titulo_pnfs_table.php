<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titulos_pnf', function (Blueprint $table) {
            $table->id('id_titulos_pnf');
            
            // Llaves Foráneas hacia PNF y Titulos
            $table->foreignId('id_pnf')->constrained('pnfs', 'id_pnf')->onDelete('restrict');
            $table->foreignId('id_titulo')->constrained('titulos', 'id_titulos')->onDelete('restrict');
            
            $table->string('nombre_titulo_pnf', 150);
            $table->timestamps(); // Solicitado en el esquema
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titulos_pnf');
    }
};