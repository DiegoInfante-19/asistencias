<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Importante añadir esta línea

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

        // Inserción de datos base
        DB::table('cohortes')->insert([
            [
                'numero_cohorte'       => 'I COHORTE',
                'fecha_inicio_cohorte' => '2023-08-01',
                'fecha_fin_cohorte'    => '2024-07-26',
                'descripcion_cohorte'  => 'Período 2023-2024',
                'estatus_cohorte'      => 'Finalizada'
            ],
            [
                'numero_cohorte'       => 'II COHORTE',
                'fecha_inicio_cohorte' => '2024-08-01',
                'fecha_fin_cohorte'    => '2025-07-27',
                'descripcion_cohorte'  => 'Período 2024-2025',
                'estatus_cohorte'      => 'Finalizada'
            ],
            [
                'numero_cohorte'       => 'III COHORTE',
                'fecha_inicio_cohorte' => '2025-11-05',
                'fecha_fin_cohorte'    => '2026-07-29',
                'descripcion_cohorte'  => 'Período 2025-2026',
                'estatus_cohorte'      => 'En curso'
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cohortes');
    }
};