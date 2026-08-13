<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos_academicos', function (Blueprint $table) {
            $table->id('id_periodo');
            $table->foreignId('id_cohortes')->constrained('cohortes', 'id_cohortes')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estatus_periodo', 50)->default('Activo');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('periodos_academicos');
    }
};