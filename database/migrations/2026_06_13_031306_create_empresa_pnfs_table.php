<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresa_pnf', function (Blueprint $table) {
            $table->id('id_empresa_pnf');
            
            // Llaves Foráneas hacia Empresas y PNF
            $table->foreignId('id_empresa')->constrained('empresas', 'id_empresa')->onDelete('restrict');
            $table->foreignId('id_pnf')->constrained('pnfs', 'id_pnf')->onDelete('restrict');
            
            $table->string('tipo_relacion', 100);
            $table->text('observacion_empresa_pnf')->nullable();
            
            // Sin timestamps en el esquema para esta tabla
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa_pnf');
    }
};