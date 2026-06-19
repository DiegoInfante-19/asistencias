<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id_rol' => 1,
                'nombre_rol' => 'Administrador',
                'descripcion_rol' => 'Acceso total al sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rol' => 2,
                'nombre_rol' => 'Coordinador',
                'descripcion_rol' => 'Gestión de PNFs y cohortes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rol' => 3,
                'nombre_rol' => 'Profesor',
                'descripcion_rol' => 'Registro de asistencias y visualización de sesiones',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('roles')->insert($roles);
    }
}