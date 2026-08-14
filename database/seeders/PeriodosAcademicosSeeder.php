<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodosAcademicosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtenemos las cohortes ya migradas/sembradas para extraer sus IDs
        $cohorte1 = DB::table('cohortes')->where('numero_cohorte', 'I Cohorte')->first();
        $cohorte2 = DB::table('cohortes')->where('numero_cohorte', 'II Cohorte')->first();
        $cohorte3 = DB::table('cohortes')->where('numero_cohorte', 'III Cohorte')->first();

        // 2. Mapeamos las fechas históricas originales de las cohortes hacia los nuevos períodos académicos
        $periodos = [
            [
                'id_cohortes'     => $cohorte1 ? $cohorte1->id_cohortes : 1,
                'fecha_inicio'    => '2023-08-01',
                'fecha_fin'       => '2024-07-26',
                'estatus_periodo' => 'Finalizado',
            ],
            [
                'id_cohortes'     => $cohorte2 ? $cohorte2->id_cohortes : 2,
                'fecha_inicio'    => '2024-08-01',
                'fecha_fin'       => '2025-07-27',
                'estatus_periodo' => 'Finalizado',
            ],
            [
                'id_cohortes'     => $cohorte3 ? $cohorte3->id_cohortes : 3,
                'fecha_inicio'    => '2025-11-05',
                'fecha_fin'       => '2026-07-29',
                'estatus_periodo' => 'Activo',
            ],
        ];

        // 3. Insertamos o actualizamos los períodos respetando la integridad referencial
        foreach ($periodos as $periodo) {
            DB::table('periodos_academicos')->updateOrInsert(
                [
                    'id_cohortes'  => $periodo['id_cohortes'],
                    'fecha_inicio' => $periodo['fecha_inicio']
                ],
                $periodo
            );
        }
    }
}