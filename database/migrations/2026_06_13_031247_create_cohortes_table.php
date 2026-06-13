<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cohortes', function (Blueprint $table) {
            $table->id('id_cohortes');
            $table->string('numero_cohorte', 20)->unique();
            $table->date('fecha_inicio_cohorte');
            $table->date('fecha_fin_cohorte');
            $table->text('descripcion_cohorte')->nullable();
            $table->string('estatus_cohorte', 50); // Ej. en curso, finalizada, proxima
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cohortes');
    }
};