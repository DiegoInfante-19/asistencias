<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TitulosPnfSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matriz de relaciones: [Nombre PNF, Nombre Título Base, Nombre Comercial del Título]
        $relaciones = [
            // Mantenimiento
            ['Mantenimiento', 'Certificado', 'ASISTENTE EN MANTENIMIENTO'],
            ['Mantenimiento', 'TSU', 'TSU EN MANTENIMIENTO'],
            ['Mantenimiento', 'Ingeniero', 'INGENIERO EN MANTENIMIENTO'],
            
            // Orfebrería y Joyería
            ['Orfebrería y joyería', 'Certificado', 'TÉCNICO ARTÍSTICO DE JOYERÍA'],
            ['Orfebrería y joyería', 'TSU', 'TSU EN ORFEBRERÍA Y JOYERÍA'],
            ['Orfebrería y joyería', 'Licenciado', 'LICENCIADO EN ORFEBRERÍA Y JOYERÍA'],
            
            // Mecánica
            ['Mecánica', 'TSU', 'TSU EN MECÁNICA'],
            ['Mecánica', 'Ingeniero', 'INGENIERO EN MECÁNICA'],
            
            // Química
            ['Química', 'Certificado', 'ASISTENTE TÉCNICO DE ANÁLISIS FÍSICO QUÍMICO'],
            ['Química', 'TSU', 'TSU EN QUÍMICA'],
            ['Química', 'Licenciado', 'LICENCIADO EN QUÍMICA'],
            
            // Sistemas de Calidad y Ambiente
            ['Sistemas de calidad y ambiente', 'TSU', 'TSU EN SISTEMAS DE CALIDAD Y AMBIENTE'],
            ['Sistemas de calidad y ambiente', 'Ingeniero', 'INGENIERO EN SISTEMAS DE CALIDAD Y AMBIENTE'],
            
            // Agroalimentación
            ['Agroalimentación', 'Certificado', 'PROMOTOR PARA EL DESARROLLO AGROALIMENTARIO FAMILIAR'],
            ['Agroalimentación', 'TSU', 'TSU EN AGROALIMENTACIÓN'],
            ['Agroalimentación', 'Certificado', 'PROMOTOR PARA EL DESARROLLO AGROALIMENTARIO COMUNITARIO'], // Trayecto III
            ['Agroalimentación', 'Ingeniero', 'INGENIERO EN AGROALIMENTACIÓN'],
            
            // Informática
            ['Informática', 'Certificado', 'SOPORTE TÉCNICO A USUARIOS Y EQUIPOS'],
            ['Informática', 'TSU', 'TSU EN INFORMÁTICA'],
            ['Informática', 'Certificado', 'DESARROLLADOR DE APLICACIONES'], // Trayecto III
            ['Informática', 'Ingeniero', 'INGENIERO EN INFORMÁTICA'],
            
            // Ingeniería de materiales industriales
            ['Ingeniería de materiales industriales', 'Certificado', 'AUXILIAR DE TALLERES Y LABORATORIOS'],
            ['Ingeniería de materiales industriales', 'TSU', 'TSU EN CERÁMICA, METALURGIA, POLÍMEROS'],
            ['Ingeniería de materiales industriales', 'Certificado', 'ANALISTA DE MATERIALES INDUSTRIALES'],
            ['Ingeniería de materiales industriales', 'Ingeniero', 'INGENIERO EN MATERIALES INDUSTRIALES'],
            
            // Higiene y Seguridad Laboral
            ['Higiene y seguridad laboral', 'TSU', 'TSU EN HIGIENE Y SEGURIDAD LABORAL'],
            ['Higiene y seguridad laboral', 'Ingeniero', 'INGENIERO EN HIGIENE Y SEGURIDAD LABORAL'],
            
            // Electricidad
            ['Electricidad', 'TSU', 'TSU EN ELECTRICIDAD'],
            ['Electricidad', 'Ingeniero', 'INGENIERO ELECTRICISTA'],
            
            // Distribución y logística
            ['Distribución y logística', 'TSU', 'TSU EN DISTRIBUCIÓN Y LOGÍSTICA'],
            ['Distribución y logística', 'Licenciado', 'LICENCIADO EN DISTRIBUCIÓN Y LOGÍSTICA'],
            
            // Electrónica
            ['Electrónica', 'TSU', 'TSU EN ELECTRÓNICA'],
            ['Electrónica', 'Ingeniero', 'INGENIERO EN ELECTRÓNICA'],
            
            // Geociencias
            ['Geociencias', 'Certificado', 'ASISTENTE TÉCNICO EN GEOCIENCIAS'],
            ['Geociencias', 'TSU', 'TSU EN GEOCIENCIAS'],
            ['Geociencias', 'Ingeniero', 'INGENIERO EN GEOCIENCIAS'],
            
            // Instrumentación y control
            ['Instrumentación y control', 'TSU', 'TSU EN INSTRUMENTACIÓN Y CONTROL'],
            ['Instrumentación y control', 'Ingeniero', 'INGENIERO EN INSTRUMENTACIÓN Y CONTROL']
        ];

        // 2. Extraemos los catálogos a memoria para evitar múltiples consultas (Pluck)
        $pnfsDB = DB::table('pnfs')->pluck('id_pnf', 'nombre_pnf');
        $titulosDB = DB::table('titulos')->pluck('id_titulos', 'nombre_titulo_base');

        // 3. Proceso de inserción relacional
        foreach ($relaciones as $fila) {
            $nombrePnf = $fila[0];
            $nombreTituloBase = $fila[1];
            $nombreTituloPnf = $fila[2];

            // Validamos que ambos padres existan en la DB antes de intentar la inserción
            if (isset($pnfsDB[$nombrePnf]) && isset($titulosDB[$nombreTituloBase])) {
                DB::table('titulos_pnf')->updateOrInsert(
                    [
                        // Criterios de búsqueda para evitar duplicados
                        'id_pnf' => $pnfsDB[$nombrePnf],
                        'id_titulo' => $titulosDB[$nombreTituloBase],
                        'nombre_titulo_pnf' => $nombreTituloPnf
                    ],
                    [
                        // Valores a insertar/actualizar
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }
    }
}
