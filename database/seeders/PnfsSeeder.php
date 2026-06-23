<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PnfsSeeder extends Seeder
{
    public function run(): void{
        $pnfs = [
            ['Agroalimentación', 'Profesional integral que aborda la cadena agroalimentaria (producción, transformación, distribución y consumo) con enfoque agroecológico para garantizar la soberanía y seguridad agroalimentaria.'],
            ['Distribución y logística', 'Profesional competente en la planificación, diseño, administración y operación de cadenas de suministro, gestionando eficientemente la distribución de bienes y servicios con valores de equidad.'],
            ['Electricidad', 'Profesional científico-técnico capacitado para la planificación, diseño, operación y mantenimiento de sistemas eléctricos industriales, automatización, control y supervisión de procesos energéticos.'],
            ['Electrónica', 'Profesional orientado a la innovación tecnológica en sistemas electrónicos de consumo, telecomunicaciones y electromedicina, aportando soluciones creativas al sector manufacturero y de salud.'],
            ['Geociencias', 'Profesional con visión integral en el área geocientífica, formado con pertinencia social para la investigación y el desarrollo sustentable, respondiendo a las necesidades del proyecto país.'],
            ['Higiene y seguridad laboral', 'Profesional con visión integral, social y ambientalista, capacitado para el diseño y gerencia de sistemas de prevención de riesgos y procesos peligrosos para garantizar entornos laborales seguros.'],
            ['Informática', 'Profesional orientado al diseño, desarrollo y puesta en marcha de proyectos sociotecnológicos, aplicando la investigación en escenarios reales para la solución de necesidades informáticas.'],
            ['Ingeniería de materiales industriales', 'Profesional integral capaz de diseñar, seleccionar, transformar y aplicar materiales de ingeniería, optimizando procesos productivos con herramientas para la prevención de fallas y soluciones ecológicas.'],
            ['Instrumentación y control', 'Profesional competente en el diseño, instalación, mantenimiento y calibración de sistemas de control e instrumentación biomédica e industrial, garantizando estándares de confiabilidad y metrología.'],
            ['Mantenimiento', 'Profesional experto en la gestión administrativa y tecnológica del mantenimiento integral de sistemas, enfocando la innovación hacia la rentabilidad, calidad, seguridad y desarrollo sustentable.'],
            ['Mecánica', 'Profesional con formación integral para el análisis, diseño, construcción, operación y mantenimiento de equipos e instalaciones industriales, optimizando recursos con responsabilidad ética y ambiental.'],
            ['Orfebrería y joyería', 'Profesional con conocimientos teóricos-prácticos en metalurgia de materiales preciosos, diseño de alta tecnología y control de calidad para la creación de piezas de alto valor artístico.'],
            ['Química', 'Profesional con talento para el análisis psicoquímico y producción de sustancias, fundamentado en valores éticos y sociales para transformar el aparato socioproductivo de la nación.'],
            ['Sistemas de calidad y ambiente', 'Profesional integral formado para optimizar procesos productivos mediante soluciones tecnológicas que aseguren el cumplimiento de estándares de calidad, normativas ambientales y la transformación socio-productiva.']
        ];

        foreach ($pnfs as $pnf) {
            DB::table('pnfs')->updateOrInsert(
                ['nombre_pnf' => $pnf[0]],
                [
                    'descripcion_pnf' => $pnf[1],
                    'vigencia_pnf' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}