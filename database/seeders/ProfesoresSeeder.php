<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\NivelAcademico;

class ProfesoresSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtenemos el usuario de pruebas asignado como Profesor
        $usuario = DB::table('users')->where('username', 'ProfPrincipal1')->first() 
                   ?? DB::table('users')->where('username', 'MaestroPrincipal')->first();
        
        // 2. Obtenemos el PNF base (Informática)
        $pnf = DB::table('pnfs')->where('nombre_pnf', 'Informática')->first();

        if ($usuario && $pnf) {
            // 3. Crear/Actualizar el perfil de Profesor especificando nivel_asignado
            DB::table('profesores')->updateOrInsert(
                ['id_users' => $usuario->id_users],
                [
                    'id_pnf'                    => $pnf->id_pnf,
                    'nivel_asignado'            => NivelAcademico::TSU->value, // Categoriado como TSU
                    'fecha_asignacion_profesor' => now()->format('Y-m-d')
                ]
            );

            // 4. Recuperar el registro recien creado del profesor
            $profesor = DB::table('profesores')->where('id_users', $usuario->id_users)->first();

            // 5. Buscar los grupos académicos activos de TSU en Informática
            $gruposTSU = DB::table('grupos_academicos')
                ->where('id_pnf', $pnf->id_pnf)
                ->where('nivel_academico', NivelAcademico::TSU->value)
                ->get();

            // 6. Asignar el profesor a los grupos académicos correspondientes en la pivote profesor_grupo
            foreach ($gruposTSU as $grupo) {
                DB::table('profesor_grupo')->updateOrInsert(
                    [
                        'id_profesor' => $profesor->id_profesor,
                        'id_grupo'    => $grupo->id_grupo,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}