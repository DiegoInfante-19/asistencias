<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesoresSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtenemos los IDs necesarios de forma dinámica
        $usuario = DB::table('users')->where('username', 'MaestroPrincipal')->first();
        $pnf     = DB::table('pnfs')->where('nombre_pnf', 'Informática')->first(); // O el PNF que prefieras

        // 2. Solo insertamos si ambos existen (Integridad referencial)
        if ($usuario && $pnf) {
            DB::table('profesores')->updateOrInsert(
                ['id_users' => $usuario->id_users],
                [
                    'id_pnf' => $pnf->id_pnf,
                    'fecha_asignacion_profesor' => now()->format('Y-m-d')
                ]
            );
        }
    }
}