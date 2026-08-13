<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('persona_formacion_academica', function (Blueprint $table) {
            $table->id('id_persona_formacion_academica');
            $table->foreignId('id_personas')->constrained('personas', 'id_personas')->onDelete('cascade');
            $table->foreignId('id_titulos_pnf')->nullable()->constrained('titulos_pnf', 'id_titulos_pnf')->onDelete('set null');
            $table->foreignId('id_titulos')->nullable()->constrained('titulos', 'id_titulos')->onDelete('set null');
            $table->text('observacion_formacion_academica')->nullable();
            
            // NUEVO CAMPO AÑADIDO DESDE EL INICIO
            $table->string('origen_formacion', 50)->default('Externo')->comment('Interno o Externo');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('persona_formacion_academica');
    }
};