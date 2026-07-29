<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('periodo_recesos', function (Blueprint $table) {
            $table->id('id_periodo_receso');
            $table->string('nombre_periodo_receso', 100)->unique();
            $table->date('fecha_inicio_periodo_receso');
            $table->date('fecha_fin_periodo_receso');
            $table->text('descripcion_periodo_receso')->nullable();
            $table->string('nivel_periodo_receso', 50);
            $table->boolean('suspension_actividades')->default(true);
            
            // NUEVA COLUMNA: Define cómo se proyectará este evento al próximo año
            $table->enum('tipo_receso', ['1', '2', '3'])->default('1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodo_recesos');
    }
};