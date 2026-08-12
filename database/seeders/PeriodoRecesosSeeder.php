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
            ['nombre' => 'Año Nuevo',                            'inicio' => '2026-01-01', 'fin' => '2026-01-01', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 
            ['nombre' => 'Día de la Juventud',                   'inicio' => '2026-02-12', 'fin' => '2026-02-12', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 
            ['nombre' => 'Carnaval (Semana)',                    'inicio' => '2026-02-16', 'fin' => '2026-02-20', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '2'], 
            ['nombre' => 'Semana Santa',                         'inicio' => '2026-03-30', 'fin' => '2026-04-03', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '2'], 
            ['nombre' => 'Declaración de la Independencia',      'inicio' => '2026-04-19', 'fin' => '2026-04-19', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 
            ['nombre' => 'Día del Trabajador',                   'inicio' => '2026-05-01', 'fin' => '2026-05-01', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 
            ['nombre' => 'Batalla de Carabobo',                  'inicio' => '2026-06-24', 'fin' => '2026-06-24', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 
            ['nombre' => 'Firma Acta de Independencia',          'inicio' => '2026-07-05', 'fin' => '2026-07-05', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 
            ['nombre' => 'Natalicio de Simón Bolívar',           'inicio' => '2026-07-24', 'fin' => '2026-07-24', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 
            ['nombre' => 'Día del Descubrimiento de América',    'inicio' => '2026-10-12', 'fin' => '2026-10-12', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 
            ['nombre' => 'Navidad y Vísperas',                   'inicio' => '2026-12-24', 'fin' => '2026-12-25', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 
            ['nombre' => 'Fin de Año',                           'inicio' => '2026-12-31', 'fin' => '2026-12-31', 'nivel' => 'Feriado Nacional', 'suspende' => true,  'tipo' => '1'], 

            // --- FECHAS CONMEMORATIVAS E INSTITUCIONALES (Sí hay clases/actividades) ---
            ['nombre' => 'Día del Trabajador Universitario',     'inicio' => '2026-03-19', 'fin' => '2026-03-19', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], 
            ['nombre' => 'Día de la Secretaria',                 'inicio' => '2026-09-30', 'fin' => '2026-09-30', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], 
            ['nombre' => 'Día del Estudiante Universitario',     'inicio' => '2026-11-21', 'fin' => '2026-11-21', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], 
            ['nombre' => 'Aniversario de la UPT Bolívar',        'inicio' => '2026-11-23', 'fin' => '2026-11-23', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], 
            ['nombre' => 'Día del Profesor Universitario',       'inicio' => '2026-12-05', 'fin' => '2026-12-05', 'nivel' => 'Institucional',  'suspende' => false, 'tipo' => '1'], 

            // --- CRONOGRAMA ACADÉMICO (Inicios, cierres, vacaciones) ---
            ['nombre' => 'Reintegro del Personal (Enero)',       'inicio' => '2026-01-12', 'fin' => '2026-01-12', 'nivel' => 'Administrativo', 'suspende' => false, 'tipo' => '3'], 
            ['nombre' => 'Inicio Trayecto Inicial',              'inicio' => '2026-01-26', 'fin' => '2026-01-26', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], 
            ['nombre' => 'Inicio Trayecto de Transición',        'inicio' => '2026-01-26', 'fin' => '2026-01-26', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], 
            ['nombre' => 'Culminación del Trayecto Inicial',     'inicio' => '2026-05-29', 'fin' => '2026-05-29', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], 
            ['nombre' => 'Fin Periodo Académico 2025-2026',      'inicio' => '2026-07-23', 'fin' => '2026-07-23', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], 
            ['nombre' => 'Inicio Periodo Vacacional (Agosto)',   'inicio' => '2026-08-03', 'fin' => '2026-09-15', 'nivel' => 'Administrativo', 'suspende' => true,  'tipo' => '3'], 
            ['nombre' => 'Reintegro del Personal (Septiembre)',  'inicio' => '2026-09-16', 'fin' => '2026-09-16', 'nivel' => 'Administrativo', 'suspende' => false, 'tipo' => '3'], 
            ['nombre' => 'Inicio Trayecto Regular 2026-2027',    'inicio' => '2026-09-28', 'fin' => '2026-09-28', 'nivel' => 'Académico',      'suspende' => false, 'tipo' => '3'], 
            ['nombre' => 'Inicio del Periodo Vacacional (Dic)',  'inicio' => '2026-12-07', 'fin' => '2026-12-31', 'nivel' => 'Administrativo', 'suspende' => true,  'tipo' => '3'], 
        ];

        foreach ($eventos as $evento) {
            // CORREGIDO: updateOrInsert hace el seeder seguro contra ejecuciones múltiples
            DB::table('periodo_recesos')->updateOrInsert(
                ['nombre_periodo_receso' => $evento['nombre']], // Llave de búsqueda (Única)
                [
                    'fecha_inicio_periodo_receso' => $evento['inicio'],
                    'fecha_fin_periodo_receso'    => $evento['fin'],
                    'nivel_periodo_receso'        => $evento['nivel'],
                    'suspension_actividades'      => $evento['suspende'],
                    'tipo_receso'                 => $evento['tipo'],
                ]
            );
        }
    }
}