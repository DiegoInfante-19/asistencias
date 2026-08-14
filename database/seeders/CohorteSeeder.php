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
                'numero_cohorte'      => 'I Cohorte',
                'descripcion_cohorte' => 'Primera cohorte académica (Sello histórico)',
                'estatus_cohorte'     => 'Finalizado',
            ],
            [
                'numero_cohorte'      => 'II Cohorte',
                'descripcion_cohorte' => 'Segunda cohorte académica (Sello histórico)',
                'estatus_cohorte'     => 'Finalizado',
            ],
            [
                'numero_cohorte'      => 'III Cohorte',
                'descripcion_cohorte' => 'Tercera cohorte académica (Sello histórico)',
                'estatus_cohorte'     => 'Activo',
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