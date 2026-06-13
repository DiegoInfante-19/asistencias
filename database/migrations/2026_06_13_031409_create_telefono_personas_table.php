<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('telefonos_personas', function (Blueprint $table) {
            $table->id('id_telefonos_personas');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('cascade');
            $table->string('numero_telefono_personas', 20);
            $table->string('tipo_telefono', 50); 
        });
    }

    public function down(): void {
        Schema::dropIfExists('telefonos_personas');
    }
};