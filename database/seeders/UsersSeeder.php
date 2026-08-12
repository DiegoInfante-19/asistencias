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
                'name_users'      => 'Admin123',
                'last_name_users' => 'Sistema',
                'cedula_users'    => '11222333', 
                'email_users'     => 'admin@universidad.edu.ve',
                'phone_users'     => '04120000000', 
                'username'        => 'AdminSiscontrol1', 
                'password_users'  => Hash::make('AAaa11**'),
          ],
                                                          [ 'rol_asignado'=>'Coordinador','name_users'=>'ViceRectorado',  'last_name_users'=>'Académico',          'cedula_users'=>'77722210', 'email_users'=>'coordinador@universidad.edu.ve', 'phone_users'=>'04121111111', 'username'=>'CoordAcad1', 'password_users'=> Hash::make('AAaa11**'),],
            /*Prof. VITRYS MILAGROS MAITA*/               [ 'rol_asignado'=>'Profesor','name_users'=>'VITRYS MILAGROS',   'last_name_users'=>'MAITA',             'cedula_users'=>'14883745', 'email_users'=>'VITRYSMAITA@GMAIL.COM',      'phone_users'=>'04128763893','username'=>'VITRYS1',         'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. GUILLERMO JOSÉ PRICE MALPICA*/        [ 'rol_asignado'=>'Profesor','name_users'=>'GUILLERMO JOSÉ',    'last_name_users'=>'PRICE MALPICA',     'cedula_users'=>'15468137', 'email_users'=>'GUILLERMOPRICE@GMAIL.COM',   'phone_users'=>'4161865453', 'username'=>'GUILLERMO1',      'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. JOSÉ LUIS CASTRO SOTO*/               [ 'rol_asignado'=>'Profesor','name_users'=>'JOSÉ LUIS',         'last_name_users'=>'CASTRO SOTO',       'cedula_users'=>'13799472', 'email_users'=>'JLCASTROS78@GMAIL.COM',      'phone_users'=>'04128720070','username'=>'CASTRO11',        'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. ROBERT ALEJANDRO FILGUEIRA CASTILLO */[ 'rol_asignado'=>'Profesor','name_users'=>'ROBERT ALEJANDRO',  'last_name_users'=>'FILGUEIRA CASTILLO','cedula_users'=>'23732346', 'email_users'=>'ROBERT10ALEJANDRO@GMAIL.COM','phone_users'=>'04140962574','username'=>'ROBERT3',         'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. KENNY URIEPERO HÉRNANDEZ*/            [ 'rol_asignado'=>'Profesor','name_users'=>'KENNY URIEPERO',    'last_name_users'=>'HÉRNANDEZ',         'cedula_users'=>'14054813', 'email_users'=>'KENNYURIEPERO@GMAIL.COM',    'phone_users'=>'04128781296','username'=>'URIEPERO3',       'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. OSKARINA DEL VALLE BOLÍVAR GÁMEZ*/    [ 'rol_asignado'=>'Profesor','name_users'=>'OSKARINA DEL VALLE','last_name_users'=>'BOLÍVAR GÁMEZ',     'cedula_users'=>'15970419', 'email_users'=>'OSKARINA.BOLIVAR@GMAIL.COM', 'phone_users'=>'04166925844','username'=>'OSKARINA25',      'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. ROGER JOSÉ MEDINA OLIVO*/             [ 'rol_asignado'=>'Profesor','name_users'=>'ROGER JOSÉ',        'last_name_users'=>'MEDINA OLIVO',      'cedula_users'=>'8894115',  'email_users'=>'RJMOLIVO@GMAIL.COM',         'phone_users'=>'04264834833','username'=>'MEDINA77',        'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. EDGLYS ROSA PRADO LANDAETA*/          [ 'rol_asignado'=>'Profesor','name_users'=>'EDGLYS ROSA',       'last_name_users'=>'PRADO LANDAETA',    'cedula_users'=>'16499093', 'email_users'=>'EDGLYSP@GMAIL.COM',          'phone_users'=>'04147619504','username'=>'EDGLYSP32',       'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. RAYMAR DANIELA CASANOVA SOTO*/        [ 'rol_asignado'=>'Profesor','name_users'=>'RAYMAR DANIELA',    'last_name_users'=>'CASANOVA SOTO',     'cedula_users'=>'19298511', 'email_users'=>'CASANOVASOTOR@GMAIL.COM',    'phone_users'=>'04162871071','username'=>'CASANOVASOTOR6',  'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. GABRIELA BETZABE MURILLO GRANADO*/    [ 'rol_asignado'=>'Profesor','name_users'=>'GABRIELA BETZABE',  'last_name_users'=>'MURILLO GRANADO',   'cedula_users'=>'11226408', 'email_users'=>'GABYMURILLOG@GMAIL.COM',     'phone_users'=>'04122176230','username'=>'GABYMURILLOG1',   'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. EDWIN ENRIQUE PERALES SALAZAR*/       [ 'rol_asignado'=>'Profesor','name_users'=>'EDWIN ENRIQUE',     'last_name_users'=>'PERALES SALAZAR',   'cedula_users'=>'18621306', 'email_users'=>'PERALESEDW@GMAIL.COM',       'phone_users'=>'04128593425','username'=>'PERALESEDW32',    'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. AMELIN PENÉLOPE PETROCINI*/           [ 'rol_asignado'=>'Profesor','name_users'=>'AMELIN PENÉLOPE',   'last_name_users'=>'PETROCINI',         'cedula_users'=>'17047035', 'email_users'=>'AMELINPETROCINI@GMAIL.COM',  'phone_users'=>'04126941398','username'=>'AMELINPE6',       'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. WILLFOR RAFAEL GOUDETH GALINDO*/      [ 'rol_asignado'=>'Profesor','name_users'=>'WILLFOR RAFAEL',    'last_name_users'=>'GOUDETH GALINDO',   'cedula_users'=>'11173641', 'email_users'=>'WILLFORGOUDETH@GMAIL.COM',   'phone_users'=>'04128588681','username'=>'WILLFORGOUDETH12','password_users'=>Hash::make('AAaa11**'),],
            /*Prof. FRANKELL TINDALL GONZÁLEZ ALVARADO*/  [ 'rol_asignado'=>'Profesor','name_users'=>'FRANKELL TINDALL',  'last_name_users'=>'GONZÁLEZ ALVARADO', 'cedula_users'=>'10387918', 'email_users'=>'FRANKELLG@GMAIL.COM',        'phone_users'=>'04143611674','username'=>'FRANKELLG7',      'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. EUCARIS DEL VALLE ÁVILA*/             [ 'rol_asignado'=>'Profesor','name_users'=>'EUCARIS DEL VALLE', 'last_name_users'=>'ÁVILA',             'cedula_users'=>'12188759', 'email_users'=>'EUCARISAVILA05@GMAIL.COM',   'phone_users'=>'04160901074','username'=>'EUCARISAVILA05',  'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. ANNYS  MILAGROS  SOFFIA*/             [ 'rol_asignado'=>'Profesor','name_users'=>'ANNYS  MILAGROS',   'last_name_users'=>'SOFFIA',            'cedula_users'=>'16648878', 'email_users'=>'SOFFIA.ANNYS@GMAIL.COM',     'phone_users'=>'04249485386','username'=>'SOFFIAYS23',      'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. RANSAY CONCEPCIÓN LOYO SOLANO*/       [ 'rol_asignado'=>'Profesor','name_users'=>'RANSAY CONCEPCIÓN', 'last_name_users'=>'LOYO SOLANO',       'cedula_users'=>'15637651', 'email_users'=>'RANSAYL@GMAIL.COM',          'phone_users'=>'04148828549','username'=>'RANSAYL22',       'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. RINA CECILIA MALUENGA PINO*/          [ 'rol_asignado'=>'Profesor','name_users'=>'RINA CECILIA',      'last_name_users'=>'MALUENGA PINO',     'cedula_users'=>'15971108', 'email_users'=>'MALUENGARINA@GMAIL.COM',     'phone_users'=>'04148565727','username'=>'MALUENGARIN13',   'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. RAMÓN ANTONIO MAITA BOLÍVAR*/         [ 'rol_asignado'=>'Profesor','name_users'=>'RAMÓN ANTONIO',     'last_name_users'=>'MAITA BOLÍVAR',     'cedula_users'=>'24377317', 'email_users'=>'RAMONMAITA06@GMAIL.COM',     'phone_users'=>'04268956859','username'=>'RAMONMAITA06',    'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. JOEL  RAMÓN POYO MANAURE*/            [ 'rol_asignado'=>'Profesor','name_users'=>'JOEL RAMÓN',        'last_name_users'=>'POYO MANAURE',      'cedula_users'=>'13919383','email_users'=>'MANAUREPJOEL@GMAIL.COM',      'phone_users'=>'04264604569','username'=>'MANAUREPJOE22',   'password_users'=>Hash::make('AAaa11**'),],
            /*Prof. NAIROBIS DEL VALLE LÓPEZ MARTÍNEZ*/   [ 'rol_asignado'=>'Profesor','name_users'=>'NAIROBIS DEL VALLE','last_name_users'=>'LÓPEZ MARTÍNEZ',    'cedula_users'=>'16914157','email_users'=>'NAIROBISLOPEZM@GMAIL.COM',    'phone_users'=>'04249070566','username'=>'NAIROBISLOPEZM94','password_users'=>Hash::make('AAaa11**'),],
            /*Prof. KARYN EVELYN MACHADO*/                [ 'rol_asignado'=>'Profesor','name_users'=>'KARYN EVELYN',      'last_name_users'=>'MACHADO',           'cedula_users'=>'14517512','email_users'=>'AXANNARELLA@GMAIL.COM',       'phone_users'=>'04163910453','username'=>'AXANNARELLA98',   'password_users'=>Hash::make('AAaa11**'),],
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