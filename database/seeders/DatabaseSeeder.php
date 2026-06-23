<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */


    public function run(): void
    {
        $this->call([
            //-----------------------------------
            // Nivel 0: Catálogos Base (Independientes)
            //-----------------------------------
            RoleSeeder::class,
            CargosSeeder::class,
            EstadosSeeder::class,
            TitulosSeeder::class,
            EstatusExpedientesSeeder::class,
            PnfsSeeder::class,
            EmpresasSeeder::class, // MOVIDO: Debe estar aquí porque es catálogo base

            //-----------------------------------
            // Nivel 1: Dependencias Simples
            //-----------------------------------
            CiudadesSeeder::class,
            TitulosPnfSeeder::class,
            UsersSeeder::class,

            //-----------------------------------
            // Nivel 2: Entidades Operativas/Vínculos
            //-----------------------------------
            ProfesoresSeeder::class,
            EmpresaPnfSeeder::class

            /* Añadir cuando tengas la data:
            CohortesSeeder::class,
            PeriodoRecesosSeeder::class,
        */
        ]);
    }
}
