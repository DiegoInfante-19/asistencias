<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CohorteSeeder extends Seeder
{
    public function run(): void
    {
        $cohortes = [
            [
                'numero_cohorte'       => 'I Cohorte',
                'fecha_inicio_cohorte' => '2023-08-01',
                'fecha_fin_cohorte'    => '2024-07-26',
                'descripcion_cohorte'  => 'Primera cohorte académica',
                'estatus_cohorte'      => 'Finalizado',
            ],
            [
                'numero_cohorte'       => 'II Cohorte',
                'fecha_inicio_cohorte' => '2024-08-01',
                'fecha_fin_cohorte'    => '2025-07-27',
                'descripcion_cohorte'  => 'Segunda cohorte académica',
                'estatus_cohorte'      => 'Finalizado',
            ],
            [
                'numero_cohorte'       => 'III Cohorte',
                'fecha_inicio_cohorte' => '2025-11-05',
                'fecha_fin_cohorte'    => '2026-07-29',
                'descripcion_cohorte'  => 'Tercera cohorte académica',
                'estatus_cohorte'      => 'Activo',
            ],
        ];

        foreach ($cohortes as $cohorte) {
            DB::table('cohortes')->updateOrInsert(
                ['numero_cohorte' => $cohorte['numero_cohorte']],
                $cohorte
            );
        }
    }
}