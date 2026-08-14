<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\NivelAcademico;
use App\Models\Profesor;
use App\Models\Seccion;

class ProfesoresSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = DB::table('users')->where('username', 'ProfPrincipal1')->first() 
                   ?? DB::table('users')->where('username', 'MaestroPrincipal')->first();
        
        $pnf = DB::table('pnfs')->where('nombre_pnf', 'Informática')->first();

        if ($usuario && $pnf) {
            DB::table('profesores')->updateOrInsert(
                ['id_users' => $usuario->id_users],
                [
                    'id_pnf'                    => $pnf->id_pnf,
                    'nivel_asignado'            => NivelAcademico::TSU->value,
                    'fecha_asignacion_profesor' => now()->format('Y-m-d')
                ]
            );

            // Recuperamos el modelo Eloquent del profesor para aprovechar las relaciones N:M
            $profesorModel = Profesor::where('id_users', $usuario->id_users)->first();

            if ($profesorModel) {
                // Buscamos las secciones disponibles de su PNF
                $seccionesPnf = Seccion::where('id_pnf', $pnf->id_pnf)->take(2)->get();

                if ($seccionesPnf->isNotEmpty()) {
                    // Sincronización masiva N:M en la tabla pivot 'profesor_seccion'
                    $profesorModel->secciones()->syncWithoutDetaching($seccionesPnf->pluck('id_seccion')->toArray());
                }
            }
        }
    }
}