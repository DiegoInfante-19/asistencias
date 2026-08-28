<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PersonasSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Instanciamos Faker con localización de Venezuela
        $faker = Faker::create('es_VE');

        // 2. Extraemos todos los IDs necesarios de las tablas maestras
        $ciudadesIds = DB::table('ciudades')->pluck('id_ciudad')->toArray();
        $cohortesIds = DB::table('cohortes')->pluck('id_cohortes')->toArray();
        $estatusExpedienteIds = DB::table('estatus_expedientes')->pluck('id_estatus_expediente')->toArray();
        $empresasIds = DB::table('empresas')->pluck('id_empresa')->toArray();
        $cargosIds = DB::table('cargos')->pluck('id_cargo')->toArray();

        // NUEVO: Extraemos TODAS las combinaciones válidas (PNF + Título) ya establecidas
        $titulosPnfValidos = DB::table('titulos_pnf')->select('id_pnf', 'id_titulo')->get()->toArray();

        // Blindaje de seguridad básico
        if (empty($ciudadesIds) || empty($cohortesIds)) {
            $this->command->error('Error: Faltan registros base (ciudades o cohortes). Ejecute los seeders correspondientes primero.');
            return;
        }

        $cantidadGenerar = 50;
        $this->command->info("Verificando y generando {$cantidadGenerar} registros de personas con historial académico (Lógica PNF validada) y laboral...");

        for ($i = 1; $i <= $cantidadGenerar; $i++) {

            // Cédula determinista (fija)
            $correlativo = str_pad($i, 4, '0', STR_PAD_LEFT);
            $cedula = '2700' . $correlativo;

            // Verificar si ya existe
            $personaExistente = DB::table('personas')->where('cedula_personas', $cedula)->first();

            if (!$personaExistente) {

                // --- PASO A: CREAR LUGAR DE NACIMIENTO ---
                $idLugarNacimiento = DB::table('lugar_nacimiento_personas')->insertGetId([
                    'id_ciudad'            => $faker->randomElement($ciudadesIds),
                    'detalles_adicionales' => $faker->optional(0.6)->streetName(),
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]);

                // --- PASO B: DATOS DE LA PERSONA ---
                $sexo = $faker->randomElement(['M', 'F']);
                $regexFiltro = '/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/u';

                $primerNombre = preg_replace($regexFiltro, '', $faker->firstName($sexo == 'M' ? 'male' : 'female'));

                $segundoNombreRaw = $faker->optional(0.5)->firstName($sexo == 'M' ? 'male' : 'female');
                $segundoNombre = $segundoNombreRaw ? preg_replace($regexFiltro, '', $segundoNombreRaw) : null;

                $primerApellido = preg_replace($regexFiltro, '', $faker->lastName());

                $segundoApellidoRaw = $faker->optional(0.5)->lastName();
                $segundoApellido = $segundoApellidoRaw ? preg_replace($regexFiltro, '', $segundoApellidoRaw) : null;

                // --- PASO C: INSERTAR PERSONA (Con su Cohorte) ---
                $idPersona = DB::table('personas')->insertGetId([
                    'cedula_personas'           => $cedula,
                    'primer_nombre_personas'    => trim($primerNombre),
                    'segundo_nombre_personas'   => $segundoNombre ? trim($segundoNombre) : null,
                    'primer_apellido_personas'  => trim($primerApellido),
                    'segundo_apellido_personas' => $segundoApellido ? trim($segundoApellido) : null,
                    'sexo_personas'             => $sexo,
                    'fecha_nacimiento_personas' => $faker->dateTimeBetween('-30 years', '-17 years')->format('Y-m-d'),
                    'id_lugar_nacimiento'       => $idLugarNacimiento,
                    'id_cohortes'               => $faker->randomElement($cohortesIds),
                    'email_personas'            => 'estudiante' . $cedula . '@universidad.edu.ve',
                    'created_at'                => now(),
                    'updated_at'                => now(),
                ]);

                // --- PASO D: TELÉFONO Y OBSERVACIÓN ---
                DB::table('telefonos_personas')->insert([
                    'id_personas'              => $idPersona,
                    'numero_telefono_personas' => $faker->randomElement(['0414', '0424', '0412', '0416', '0426']) . $faker->numerify('#######'),
                    'tipo_telefono'            => 'Móvil Principal'
                ]);

                if ($faker->boolean(30)) {
                    DB::table('observacion_personas')->insert([
                        'id_personas'          => $idPersona,
                        'observacion_personas' => $faker->realText(150),
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ]);
                }

                // =========================================================
                // NUEVO: DATOS PARA PROBAR LOS FILTROS AVANZADOS (Corregido)
                // =========================================================

                // --- PASO E: EXPEDIENTE DE TITULACIÓN ---
                // Ahora usamos las combinaciones válidas de titulos_pnf
                if ($faker->boolean(80) && !empty($titulosPnfValidos) && !empty($estatusExpedienteIds)) {
                    
                    // Elegimos una combinación real al azar (Ej: Informática + TSU)
                    $combinacionRandom = $faker->randomElement($titulosPnfValidos);

                    DB::table('titulacion_personas')->insert([
                        'id_personas'           => $idPersona,
                        'id_pnf'                => $combinacionRandom->id_pnf,        // PNF validado
                        'id_titulacion'         => $combinacionRandom->id_titulo,     // Título validado para ese PNF
                        'id_estatus_expediente' => $faker->randomElement($estatusExpedienteIds)
                    ]);
                }

                // --- PASO F: PERFIL LABORAL ---
                if ($faker->boolean(60) && !empty($empresasIds) && !empty($cargosIds)) {
                    DB::table('empresa_personas')->insert([
                        'id_personas' => $idPersona,
                        'id_empresa'  => $faker->randomElement($empresasIds),
                        'id_cargo'    => $faker->randomElement($cargosIds)
                    ]);
                }
            }
        }
    }
}