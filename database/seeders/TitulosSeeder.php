<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TitulosSeeder extends Seeder{
    public function run(): void{
        // Solo Títulos Base Puros (Nivel 0)
        $titulos = [
            // Educación Media
            ['nombre_titulo_base' => 'Bachiller en Ciencias',    'nivel_academico' => 'Media'],
            ['nombre_titulo_base' => 'Bachiller en Humanidades', 'nivel_academico' => 'Media'],
            ['nombre_titulo_base' => 'Bachiller Integral',       'nivel_academico' => 'Media'],
            ['nombre_titulo_base' => 'Técnico Medio',            'nivel_academico' => 'Media Técnica'],
            
            // Certificaciones
            ['nombre_titulo_base' => 'Certificado', 'nivel_academico' => 'Certificación Corta'],
            ['nombre_titulo_base' => 'Técnico',     'nivel_academico' => 'Técnica'],
            
            // Educación Universitaria de Pregrado
            ['nombre_titulo_base' => 'TSU',        'nivel_academico' => 'Universitaria'],
            ['nombre_titulo_base' => 'Licenciado', 'nivel_academico' => 'Universitaria'],
            ['nombre_titulo_base' => 'Ingeniero',  'nivel_academico' => 'Universitaria'],
            ['nombre_titulo_base' => 'Abogado',    'nivel_academico' => 'Universitaria'],
            ['nombre_titulo_base' => 'Profesor',   'nivel_academico' => 'Universitaria'],
            
            // Postgrado
            ['nombre_titulo_base' => 'Especialista', 'nivel_academico' => 'Postgrado'], 
            ['nombre_titulo_base' => 'Magister',     'nivel_academico' => 'Postgrado'], 
            ['nombre_titulo_base' => 'Doctor',       'nivel_academico' => 'Postgrado'], 
        ];

        foreach ($titulos as $titulo) {
            DB::table('titulos')->updateOrInsert(
                ['nombre_titulo_base' => $titulo['nombre_titulo_base']],
                ['nivel_academico' => $titulo['nivel_academico']]
            );
        }
    }
}




// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

// class TitulosSeeder extends Seeder{
//     public function run(): void{
//         $titulos = [
//             ['nombre_titulo_base' => 'Bachiller',     'nivel_academico' => 'Media'],
//             ['nombre_titulo_base' => 'Técnico Medio', 'nivel_academico' => 'Media Técnica'],
//             ['nombre_titulo_base' => 'Certificado',   'nivel_academico' => 'Certificación Corta'],
//             ['nombre_titulo_base' => 'Técnico',       'nivel_academico' => 'Técnica'],
//             ['nombre_titulo_base' => 'TSU',           'nivel_academico' => 'Universitaria'],
//             ['nombre_titulo_base' => 'Licenciado',    'nivel_academico' => 'Universitaria'],
//             ['nombre_titulo_base' => 'Ingeniero',     'nivel_academico' => 'Universitaria'],
//             ['nombre_titulo_base' => 'Especialista',  'nivel_academico' => 'Postgrado'], 
//         ];
//         foreach ($titulos as $titulo) {
//             DB::table('titulos')->updateOrInsert(
//                 ['nombre_titulo_base' => $titulo['nombre_titulo_base']],
//                 ['nivel_academico' => $titulo['nivel_academico']]
//             );
//         }
//     }
// }