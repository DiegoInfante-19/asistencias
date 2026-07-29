<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodoRecesosSeeder extends Seeder
{
    public function run(): void
    {
        $eventos = [
            // --- FERIADOS CON SUSPENSIÓN DE ACTIVIDADES (Nadie va a clase) ---
            ['nombre' => 'Año Nuevo',                            'inicio' => '2026-01-01', 'fin' => '2026-01-01', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo
            ['nombre' => 'Día de la Juventud',                   'inicio' => '2026-02-12', 'fin' => '2026-02-12', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo
            ['nombre' => 'Carnaval (Semana)',                    'inicio' => '2026-02-16', 'fin' => '2026-02-20', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '2'], // Móvil (Depende de Pascua)
            ['nombre' => 'Semana Santa',                         'inicio' => '2026-03-30', 'fin' => '2026-04-03', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '2'], // Móvil (Depende de Pascua)
            ['nombre' => 'Declaración de la Independencia',      'inicio' => '2026-04-19', 'fin' => '2026-04-19', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo
            ['nombre' => 'Día del Trabajador',                   'inicio' => '2026-05-01', 'fin' => '2026-05-01', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo
            ['nombre' => 'Batalla de Carabobo',                  'inicio' => '2026-06-24', 'fin' => '2026-06-24', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo
            ['nombre' => 'Firma Acta de Independencia',          'inicio' => '2026-07-05', 'fin' => '2026-07-05', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo
            ['nombre' => 'Natalicio de Simón Bolívar',           'inicio' => '2026-07-24', 'fin' => '2026-07-24', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo
            ['nombre' => 'Día del Descubrimiento de América',    'inicio' => '2026-10-12', 'fin' => '2026-10-12', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo
            ['nombre' => 'Navidad y Vísperas',                   'inicio' => '2026-12-24', 'fin' => '2026-12-25', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo
            ['nombre' => 'Fin de Año',                           'inicio' => '2026-12-31', 'fin' => '2026-12-31', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], // Fijo

            // --- FECHAS CONMEMORATIVAS E INSTITUCIONALES (Sí hay clases/actividades) ---
            ['nombre' => 'Día del Trabajador Universitario',     'inicio' => '2026-03-19', 'fin' => '2026-03-19', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], // Fijo
            ['nombre' => 'Día de la Secretaria',                 'inicio' => '2026-09-30', 'fin' => '2026-09-30', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], // Fijo
            ['nombre' => 'Día del Estudiante Universitario',     'inicio' => '2026-11-21', 'fin' => '2026-11-21', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], // Fijo
            ['nombre' => 'Aniversario de la UPT Bolívar',        'inicio' => '2026-11-23', 'fin' => '2026-11-23', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], // Fijo
            ['nombre' => 'Día del Profesor Universitario',       'inicio' => '2026-12-05', 'fin' => '2026-12-05', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], // Fijo

            // --- CRONOGRAMA ACADÉMICO (Inicios, cierres, vacaciones) ---
            ['nombre' => 'Reintegro del Personal',               'inicio' => '2026-01-12', 'fin' => '2026-01-12', 'nivel' => 'Administrativo', 'suspende' => false, 'tipo' => '3'], // Único (varía de día exacto cada año según calendario administrativo)
            ['nombre' => 'Inicio Trayecto Inicial',              'inicio' => '2026-01-26', 'fin' => '2026-01-26', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], // Único
            ['nombre' => 'Inicio Trayecto de Transición/Nivelación','inicio' => '2026-01-26', 'fin' => '2026-01-26', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], // Único
            ['nombre' => 'Culminación del Trayecto Inicial',     'inicio' => '2026-05-29', 'fin' => '2026-05-29', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], // Único
            ['nombre' => 'Fin Periodo Académico 2025-2026',      'inicio' => '2026-07-23', 'fin' => '2026-07-23', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], // Único
            ['nombre' => 'Inicio Periodo Vacacional (Agosto)',   'inicio' => '2026-08-03', 'fin' => '2026-09-15', 'nivel' => 'Administrativo', 'suspende' => true,  'tipo' => '3'], // Único
            ['nombre' => 'Reintegro del Personal',               'inicio' => '2026-09-16', 'fin' => '2026-09-16', 'nivel' => 'Administrativo', 'suspende' => false, 'tipo' => '3'], // Único
            ['nombre' => 'Inicio Trayecto Regular 2026-2027',    'inicio' => '2026-09-28', 'fin' => '2026-09-28', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], // Único
            ['nombre' => 'Inicio del Periodo Vacacional (Diciembre)','inicio' => '2026-12-07', 'fin' => '2026-12-31', 'nivel' => 'Administrativo', 'suspende' => true,  'tipo' => '3'], // Único
        ];

        foreach ($eventos as $evento) {
            DB::table('periodo_recesos')->insert([
                'nombre_periodo_receso'       => $evento['nombre'],
                'fecha_inicio_periodo_receso' => $evento['inicio'],
                'fecha_fin_periodo_receso'    => $evento['fin'],
                'nivel_periodo_receso'        => $evento['nivel'],
                'suspension_actividades'      => $evento['suspende'],
                'tipo_receso'                 => $evento['tipo'], // <--- Añadido mapeado correctamente
            ]);
        }
    }
}