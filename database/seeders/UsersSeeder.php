<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder{
    public function run(): void{
        // 1. Extraemos los roles a memoria (Llave Natural -> ID)
        $rolesDB = DB::table('roles')->pluck('id_rol', 'nombre_rol');
        // 2. Definimos los 3 usuarios iniciales cumpliendo estrictamente con las Regex
        $usuarios = [
            [
                'rol_asignado'    => 'Administrador',
                'name_users'      => 'Admin',
                'last_name_users' => 'Sistema',
                'cedula_users'    => '99912210', 
                'email_users'     => 'admin@universidad.edu.ve',
                'phone_users'     => '04120000000', 
                'username'        => 'AdminSiscontrol1', 
                'password_users'  => Hash::make('AAdd55**'), 
            ],
            [
                'rol_asignado'    => 'Coordinador',
                'name_users'      => 'ViceRectorado',
                'last_name_users' => 'Académico',
                'cedula_users'    => '77722210',
                'email_users'     => 'coordinador@universidad.edu.ve',
                'phone_users'     => '04121111111', 
                'username'        => 'CoordAcad1', 
                'password_users'  => Hash::make('Coord1234*'),
            ],
            [
                'rol_asignado'    => 'Profesor',
                'name_users'      => 'Principal',
                'last_name_users' => 'Maestro',
                'cedula_users'    => '88842210',
                'email_users'     => 'maestro@universidad.edu.ve',
                'phone_users'     => '04122222222', 
                'username'        => 'ProfPrincipal1', 
                'password_users'  => Hash::make('Profesor1234*'),
            ]
        ];

        // 3. Proceso de inserción y creación de preguntas secretas
        foreach ($usuarios as $usuario) {
            if (isset($rolesDB[$usuario['rol_asignado']])) {
                // Insertar/Actualizar Usuario
                DB::table('users')->updateOrInsert(
                    ['cedula_users' => $usuario['cedula_users']],
                    [
                        'name_users'        => $usuario['name_users'],
                        'last_name_users'   => $usuario['last_name_users'],
                        'email_users'       => $usuario['email_users'],
                        'email_verified_at' => now(),
                        'phone_users'       => $usuario['phone_users'],
                        'username'          => $usuario['username'],
                        'status_users'      => 'Activo',
                        'id_rol'            => $rolesDB[$usuario['rol_asignado']],
                        'password_users'    => $usuario['password_users'],
                        'created_at'        => now(),
                        'updated_at'        => now()
                    ]
                );

                // Obtener el usuario recién insertado para sacar su ID
                $userRecienCreado = DB::table('users')->where('cedula_users', $usuario['cedula_users'])->first();
                // Crear/Inicializar preguntas secretas (usando valores placeholder para cumplir con el NOT NULL)
                DB::table('preguntas_secretas')->updateOrInsert(
                    ['id_users' => $userRecienCreado->id_users],
                    [
                        'pregunta1'  => 'PENDIENTE',
                        'respuesta1' => 'PENDIENTE',
                        'pregunta2'  => 'PENDIENTE',
                        'respuesta2' => 'PENDIENTE'
                    ]
                );
            }
        }
    }
}


// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;

// class UsersSeeder extends Seeder
// {
//     public function run(): void
//     {
//         // 1. Extraemos los roles a memoria (Llave Natural -> ID)
//         $rolesDB = DB::table('roles')->pluck('id_rol', 'nombre_rol');
        
//         // 2. Definimos los 3 usuarios iniciales
//         $usuarios = [
//             [
//                 'rol_asignado'    => 'Administrador', 
//                 'name_users'      => 'Admin',
//                 'last_name_users' => 'Sistema',
//                 'cedula_users'    => '99912210',
//                 'email_users'     => 'admin@universidad.edu.ve',
//                 'phone_users'     => '0400-0000000',
//                 'username'        => 'AdminSiscontrolAsistencia',
//                 'password_users'  => Hash::make('AAdd55**'), 
//             ],
//             [
//                 'rol_asignado'    => 'Coordinador',
//                 'name_users'      => 'ViceRectorado',
//                 'last_name_users' => 'Académico',
//                 'cedula_users'    => '77722210',
//                 'email_users'     => 'coordinador@universidad.edu.ve',
//                 'phone_users'     => '0400-1111111',
//                 'username'        => 'ViReAcademico',
//                 'password_users'  => Hash::make('Coord1234*'),
//             ],
//             [
//                 'rol_asignado'    => 'Profesor',
//                 'name_users'      => 'Principal',
//                 'last_name_users' => 'Maestro',
//                 'cedula_users'    => '88842210',
//                 'email_users'     => 'maestro@universidad.edu.ve',
//                 'phone_users'     => '0400-2222222',
//                 'username'        => 'MaestroPrincipal',
//                 'password_users'  => Hash::make('Profesor1234*'),
//             ]
//         ];

//         // 3. Proceso de inserción y creación de preguntas secretas
//         foreach ($usuarios as $usuario) {

//             if (isset($rolesDB[$usuario['rol_asignado']])) {
                
//                 // Insertar/Actualizar Usuario
//                 DB::table('users')->updateOrInsert(
//                     ['cedula_users' => $usuario['cedula_users']],
//                     [
//                         'name_users'        => $usuario['name_users'],
//                         'last_name_users'   => $usuario['last_name_users'],
//                         'email_users'       => $usuario['email_users'],
//                         'email_verified_at' => now(),
//                         'phone_users'       => $usuario['phone_users'],
//                         'username'          => $usuario['username'],
//                         'status_users'      => 'Activo', 
//                         'id_rol'            => $rolesDB[$usuario['rol_asignado']],
//                         'password_users'    => $usuario['password_users'],
//                         'created_at'        => now(),
//                         'updated_at'        => now()
//                     ]
//                 );

//                 // Obtener el usuario recién insertado para sacar su ID
//                 $userRecienCreado = DB::table('users')->where('cedula_users', $usuario['cedula_users'])->first();

//                 // Crear/Inicializar preguntas secretas (usando valores placeholder para cumplir con el NOT NULL)
//                 DB::table('preguntas_secretas')->updateOrInsert(
//                     ['id_users' => $userRecienCreado->id_users],
//                     [
//                         'pregunta1'  => 'PENDIENTE',
//                         'respuesta1' => 'PENDIENTE',
//                         'pregunta2'  => 'PENDIENTE',
//                         'respuesta2' => 'PENDIENTE'
//                     ]
//                 );
//             }
//         }
//     }
// }