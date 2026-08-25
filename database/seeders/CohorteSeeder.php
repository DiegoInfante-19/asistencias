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
                'numero_cohorte'      => 'I COHORTE',
                'descripcion_cohorte' => 'Período 2023-2024',
                'estatus_cohorte'     => 'Finalizada',
            ],
            [
                'numero_cohorte'      => 'II COHORTE',
                'descripcion_cohorte' => 'Período 2024-2025',
                'estatus_cohorte'     => 'Finalizada',
            ],
            [
                'numero_cohorte'      => 'III COHORTE',
                'descripcion_cohorte' => 'Período 2025-2026',
                'estatus_cohorte'     => 'En curso',
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