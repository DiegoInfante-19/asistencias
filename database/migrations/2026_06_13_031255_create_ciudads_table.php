<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id('id_ciudad');
            
            // Llave Foránea hacia Estados
            $table->foreignId('id_estado')->constrained('estados', 'id_estado')->onDelete('restrict');
            
            $table->string('nombre_ciudad', 100);
            // Sin timestamps por ser catálogo fijo
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};