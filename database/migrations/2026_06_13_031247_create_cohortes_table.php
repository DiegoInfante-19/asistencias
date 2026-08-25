<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cohortes', function (Blueprint $table) {
            $table->id('id_cohortes');
            $table->string('numero_cohorte', 20)->unique();
            $table->text('descripcion_cohorte')->nullable();
            $table->string('estatus_cohorte', 50);
        });

        // Inserción de datos base sincronizados con el modelo
        DB::table('cohortes')->insert([
            ['numero_cohorte' => 'I COHORTE', 'descripcion_cohorte' => 'Período 2023-2024', 'estatus_cohorte' => 'Finalizada'],
            ['numero_cohorte' => 'II COHORTE', 'descripcion_cohorte' => 'Período 2024-2025', 'estatus_cohorte' => 'Finalizada'],
            ['numero_cohorte' => 'III COHORTE', 'descripcion_cohorte' => 'Período 2025-2026', 'estatus_cohorte' => 'En curso']
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cohortes');
    }
};