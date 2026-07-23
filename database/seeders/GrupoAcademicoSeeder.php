<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cohorte;
use App\Models\Pnf;
use App\Models\GrupoAcademico;
use App\Enums\NivelAcademico;

class GrupoAcademicoSeeder extends Seeder
{
    public function run(): void
    {
        $cohortes = Cohorte::all();
        $pnfs = Pnf::all();

        // Si no existen cohortes o PNFs registrados, se interrumpe para evitar inconsistencias
        if ($cohortes->isEmpty() || $pnfs->isEmpty()) {
            return;
        }

        // Genera los sub-grupos (TSU e Ingeniería) para cada combinación PNF-Cohorte
        foreach ($cohortes as $cohorte) {
            foreach ($pnfs as $pnf) {
                // 1. Grupo TSU
                GrupoAcademico::firstOrCreate([
                    'id_cohortes'     => $cohorte->id_cohortes,
                    'id_pnf'          => $pnf->id_pnf,
                    'nivel_academico' => NivelAcademico::TSU,
                ], [
                    'estatus_grupo'   => 'Activo',
                ]);

                // 2. Grupo Ingeniería
                GrupoAcademico::firstOrCreate([
                    'id_cohortes'     => $cohorte->id_cohortes,
                    'id_pnf'          => $pnf->id_pnf,
                    'nivel_academico' => NivelAcademico::INGENIERIA,
                ], [
                    'estatus_grupo'   => 'Activo',
                ]);
            }
        }
    }
}