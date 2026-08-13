<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secciones', function (Blueprint $table) {
            $table->id('id_seccion');
            $table->foreignId('id_periodo')->constrained('periodos_academicos', 'id_periodo')->onDelete('cascade');
            $table->foreignId('id_pnf')->constrained('pnfs', 'id_pnf')->onDelete('cascade');
            $table->string('nombre_seccion', 50);
            $table->string('estatus_seccion', 50)->default('Activo');
            $table->timestamps();

            $table->unique(['id_periodo', 'id_pnf', 'nombre_seccion'], 'uk_seccion_periodo_pnf');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('secciones');
    }
};