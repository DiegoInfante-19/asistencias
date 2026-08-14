<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seccion;
use App\Models\PeriodoAcademico;
use App\Models\Pnf;

class SeccionesSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = PeriodoAcademico::all();
        $pnfs = Pnf::all();

        if ($periodos->isEmpty() || $pnfs->isEmpty()) {
            return;
        }

        // Generamos secciones de prueba por cada período y PNF disponible
        foreach ($periodos as $periodo) {
            foreach ($pnfs as $pnf) {
                // Creamos un par de secciones por PNF y período (Ej: M1, M2)
                Seccion::create([
                    'id_periodo'      => $periodo->id_periodo,
                    'id_pnf'          => $pnf->id_pnf,
                    'nombre_seccion'  => 'Sec-' . strtoupper(substr($pnf->nombre_pnf, 0, 3)) . '-01',
                    'estatus_seccion' => 'Activa',
                ]);

                Seccion::create([
                    'id_periodo'      => $periodo->id_periodo,
                    'id_pnf'          => $pnf->id_pnf,
                    'nombre_seccion'  => 'Sec-' . strtoupper(substr($pnf->nombre_pnf, 0, 3)) . '-02',
                    'estatus_seccion' => 'Activa',
                ]);
            }
        }
    }
}