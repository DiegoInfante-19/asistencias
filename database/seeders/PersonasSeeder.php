<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PersonasSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Instanciamos Faker con localización de Venezuela para datos realistas
        $faker = Faker::create('es_VE');

        // 2. Extraemos las ciudades existentes para no violar la llave foránea
        $ciudadesIds = DB::table('ciudades')->pluck('id_ciudad')->toArray();

        // Blindaje: Verificar que el CiudadesSeeder ya se haya ejecutado
        if (empty($ciudadesIds)) {
            $this->command->error('Error: No hay ciudades en la base de datos. Ejecute CiudadesSeeder primero.');
            return;
        }

        // 3. Cantidad de personas a generar
        $cantidadGenerar = 50;
        $this->command->info("Verificando y generando {$cantidadGenerar} registros de personas...");

        for ($i = 1; $i <= $cantidadGenerar; $i++) {
            
            // Generamos una cédula determinista (fija) de 8 dígitos para que sea idempotente
            // Ej: 27000001, 27000050. Cumple exactamente con la regex: /^\d{6,8}$/
            $correlativo = str_pad($i, 4, '0', STR_PAD_LEFT);
            $cedula = '2700' . $correlativo;

            // Verificamos si la persona ya existe para evitar errores SQL por duplicidad
            $personaExistente = DB::table('personas')->where('cedula_personas', $cedula)->first();

            // Si la persona NO existe, procedemos a crear su lugar de nacimiento y luego su perfil
            if (!$personaExistente) {
                
                // --- PASO A: CREAR LUGAR DE NACIMIENTO ---
                // Se elige una ciudad al azar y se respeta el max:255 de detalles_adicionales
                $idCiudadRandom = $faker->randomElement($ciudadesIds);
                
                $idLugarNacimiento = DB::table('lugar_nacimiento_personas')->insertGetId([
                    'id_ciudad'            => $idCiudadRandom,
                    'detalles_adicionales' => $faker->optional(0.6)->streetName(), // 60% de prob.
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]);

                // --- PASO B: PREPARAR DATOS RESPETANDO REGEX ---
                $sexo = $faker->randomElement(['M', 'F']);
                
                // Limpiamos los nombres generados por Faker para asegurarnos de que SOLO contengan letras y espacios.
                // Esto previene que Faker devuelva títulos como "Dr." o "Sr." que romperían tu regex.
                $regexFiltro = '/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/u';
                
                $primerNombreRaw = $faker->firstName($sexo == 'M' ? 'male' : 'female');
                $primerNombre = preg_replace($regexFiltro, '', $primerNombreRaw);
                
                $segundoNombreRaw = $faker->optional(0.5)->firstName($sexo == 'M' ? 'male' : 'female');
                $segundoNombre = $segundoNombreRaw ? preg_replace($regexFiltro, '', $segundoNombreRaw) : null;
                
                $primerApellidoRaw = $faker->lastName();
                $primerApellido = preg_replace($regexFiltro, '', $primerApellidoRaw);
                
                $segundoApellidoRaw = $faker->optional(0.5)->lastName();
                $segundoApellido = $segundoApellidoRaw ? preg_replace($regexFiltro, '', $segundoApellidoRaw) : null;

                // --- PASO C: INSERTAR PERSONA ---
                $idPersona = DB::table('personas')->insertGetId([
                    'cedula_personas'           => $cedula,
                    'primer_nombre_personas'    => trim($primerNombre),
                    'segundo_nombre_personas'   => $segundoNombre ? trim($segundoNombre) : null,
                    'primer_apellido_personas'  => trim($primerApellido),
                    'segundo_apellido_personas' => $segundoApellido ? trim($segundoApellido) : null,
                    'sexo_personas'             => $sexo,
                    // Edad válida (before:today) universitaria
                    'fecha_nacimiento_personas' => $faker->dateTimeBetween('-30 years', '-17 years')->format('Y-m-d'),
                    'id_lugar_nacimiento'       => $idLugarNacimiento,
                    // Email que cumple estrictamente la regex
                    'email_personas'            => 'estudiante' . $cedula . '@universidad.edu.ve',
                    'created_at'                => now(),
                    'updated_at'                => now(),
                ]);

                // --- PASO D: INSERTAR TELÉFONO (Opcional pero recomendado para pruebas) ---
                DB::table('telefonos_personas')->insert([
                    'id_personas'              => $idPersona,
                    'numero_telefono_personas' => $faker->randomElement(['0414', '0424', '0412', '0416', '0426']) . $faker->numerify('#######'),
                    'tipo_telefono'            => 'Móvil Principal'
                ]);

                // --- PASO E: INSERTAR OBSERVACIÓN (Usando la lógica de StoreObservacionPersonaRequest) ---
                // Para darle más realismo, el 30% de los estudiantes tendrá una observación
                if ($faker->boolean(30)) {
                    DB::table('observacion_personas')->insert([
                        'id_personas'          => $idPersona,
                        'observacion_personas' => $faker->realText(150), // Máximo 1000 según tu FormRequest
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ]);
                }
            }
        }
    }
}