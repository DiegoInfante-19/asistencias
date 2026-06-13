<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('empresa_personas', function (Blueprint $table) {
            $table->id('id_empresa_personas');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('cascade');
            $table->foreignId('id_empresa')->constrained('empresas', 'id_empresa')->onDelete('restrict');
            $table->foreignId('id_cargo')->constrained('cargos', 'id_cargo')->onDelete('restrict');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('empresa_personas');
    }
};