<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seccion;
use App\Models\PeriodoAcademico;
use App\Models\Pnf;
use Illuminate\Support\Str; // <-- IMPORTANTE: Agregamos el helper de Laravel

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
                // 1. Quita acentos (Química -> Quimica)
                // 2. Extrae 3 letras de forma segura
                // 3. Lo convierte a mayúsculas
                $prefijoPnf = Str::upper(Str::substr(Str::ascii($pnf->nombre_pnf), 0, 3));

                // Creamos un par de secciones por PNF y período (Ej: Sec-QUI-01)
                Seccion::create([
                    'id_periodo'      => $periodo->id_periodo,
                    'id_pnf'          => $pnf->id_pnf,
                    'nombre_seccion'  => 'Sec-' . $prefijoPnf . '-01',
                    'estatus_seccion' => 'Activa',
                ]);

                Seccion::create([
                    'id_periodo'      => $periodo->id_periodo,
                    'id_pnf'          => $pnf->id_pnf,
                    'nombre_seccion'  => 'Sec-' . $prefijoPnf . '-02',
                    'estatus_seccion' => 'Activa',
                ]);
            }
        }
    }
}