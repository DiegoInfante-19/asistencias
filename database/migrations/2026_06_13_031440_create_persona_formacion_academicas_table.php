<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('persona_formacion_academica', function (Blueprint $table) {
            $table->id('id_persona_formacion_academica');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('cascade');
            
            // Relaciones de títulos (ambas nullable para flexibilidad de origen)
            $table->foreignId('id_titulos_pnf')->nullable()->constrained('titulos_pnf', 'id_titulos_pnf')->onDelete('set null');
            $table->foreignId('id_titulos')->nullable()->constrained('titulos', 'id_titulos')->onDelete('set null');
            
            // Nuevos metadatos avanzados para títulos específicos y externos
            $table->string('origen_formacion', 50)->default('Externo')->comment('Interno o Externo');
            $table->string('mencion_especifica', 150)->nullable()->comment('Ej. en Computación, en Gerencia...');
            $table->string('institucion_emisora', 200)->nullable()->comment('Universidad o instituto de procedencia');
            $table->smallInteger('anio_egreso')->unsigned()->nullable()->comment('Año de graduación');
            
            $table->text('observacion_formacion_academica')->nullable();
            
            // RESTRICCIÓN DE UNICIDAD COMPUESTA: Compatible con SoftDeletes a nivel de motor
            // Permite reingresar registros eliminados lógicamente sin colisiones SQL 23000
            $table->unique(['id_personas', 'id_titulos_pnf', 'deleted_at'], 'uq_persona_titulo_pnf_soft');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('persona_formacion_academica');
    }
};