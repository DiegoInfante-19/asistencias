<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('acreditaciones', function (Blueprint $table) {
            $table->id('id_acreditaciones');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('cascade');
            $table->foreignId('id_empresa')->constrained('empresas', 'id_empresa')->onDelete('restrict');
            $table->foreignId('id_pnf')->constrained('pnfs', 'id_pnf')->onDelete('restrict');
            $table->string('estatus_acreditacion', 50);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('acreditaciones');
    }
};