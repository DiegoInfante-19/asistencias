<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaPnfSeeder extends Seeder
{
    public function run(): void{
        $relaciones = [
            'CVG ALCASA' => ['Electricidad', 'Higiene y seguridad laboral', 'Informática', 'Ingeniería de materiales industriales', 'Instrumentación y control', 'Mecánica', 'Sistemas de calidad y ambiente'],
            'CVG BAUXILUM' => ['Distribución y logística', 'Electricidad', 'Electrónica', 'Higiene y seguridad laboral', 'Informática', 'Ingeniería de materiales industriales', 'Instrumentación y control', 'Mantenimiento', 'Mecánica', 'Química'],
            'CVG BRIQCAR/VENPRECAR' => ['Distribución y logística', 'Ingeniería de materiales industriales', 'Mantenimiento', 'Mecánica'],
            'CVG BRIQUETERA DEL ORINOCO/ORINOCO IRON' => ['Distribución y logística', 'Electricidad', 'Higiene y seguridad laboral', 'Informática', 'Ingeniería de materiales industriales', 'Mantenimiento', 'Mecánica', 'Sistemas de calidad y ambiente'],
            'CVG BRIQVEN/MATESI' => ['Distribución y logística', 'Electricidad', 'Higiene y seguridad laboral', 'Ingeniería de materiales industriales', 'Instrumentación y control', 'Mecánica'],
            'CVG CABELUM' => ['Distribución y logística', 'Electricidad', 'Higiene y seguridad laboral', 'Ingeniería de materiales industriales', 'Mantenimiento', 'Mecánica'],
            'CVG CARBONORCA' => ['Distribución y logística', 'Electricidad', 'Higiene y seguridad laboral', 'Ingeniería de materiales industriales', 'Mecánica', 'Sistemas de calidad y ambiente'],
            'CVG CASA MATRIZ' => ['Higiene y seguridad laboral', 'Mantenimiento'],
            'CVG COMSIGUA' => ['Higiene y seguridad laboral', 'Informática', 'Mecánica', 'Química', 'Sistemas de calidad y ambiente'],
            'CVG FERROCASA' => ['Electricidad', 'Informática', 'Mecánica'],
            'CVG FERROMINERA DEL ORINOCO' => ['Electricidad', 'Electrónica', 'Higiene y seguridad laboral', 'Ingeniería de materiales industriales', 'Instrumentación y control', 'Mantenimiento', 'Mecánica', 'Química'],
            'CVG LOGÍSTICA' => ['Distribución y logística', 'Mecánica'],
            'CVG MINERVEN' => ['Geociencias', 'Higiene y seguridad laboral', 'Mecánica', 'Orfebrería y joyería', 'Química'],
            'CVG SIDOR' => ['Distribución y logística', 'Higiene y seguridad laboral', 'Mecánica'],
            'CVG VENALUM' => ['Distribución y logística', 'Electricidad', 'Higiene y seguridad laboral', 'Informática', 'Ingeniería de materiales industriales', 'Instrumentación y control', 'Mantenimiento', 'Mecánica'],
        ];
        // 2. Cargamos catálogos en memoria
        $empresasDB = DB::table('empresas')->pluck('id_empresa', 'nombre_empresa');
        $pnfsDB = DB::table('pnfs')->pluck('id_pnf', 'nombre_pnf');
        // 3. Procesamiento y carga
        foreach ($relaciones as $nombreEmpresa => $listaPnfs) {
            foreach ($listaPnfs as $nombrePnf) {
                // Validación estricta usando nombres normalizados
                if (isset($empresasDB[$nombreEmpresa]) && isset($pnfsDB[$nombrePnf])) {
                    DB::table('empresa_pnf')->updateOrInsert(
                        [
                            'id_empresa' => $empresasDB[$nombreEmpresa],
                            'id_pnf'     => $pnfsDB[$nombrePnf]
                        ],
                        [
                            'tipo_relacion' => 'Convenio Académico' // O el valor que prefieras por defecto
                        ]
                    );
                }
            }
        }
    }
}