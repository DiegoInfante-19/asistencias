<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            // Nivel 0: Catálogos Base (Sin dependencias)
            RoleSeeder::class,
            CargosSeeder::class,
            EstadosSeeder::class,
            TitulosSeeder::class,
            EstatusExpedientesSeeder::class,
            PnfsSeeder::class,
            EmpresasSeeder::class,
            PeriodoRecesosSeeder::class,
            CohorteSeeder::class, // Sello estático

            // Nivel 1: Entidades con dependencias directas
            CiudadesSeeder::class,
            TitulosPnfSeeder::class,
            UsersSeeder::class,
            EmpresaPnfSeeder::class,

            // Nivel 2: Temporalidad y Estructura Académica (Reemplaza a Grupos)
            PeriodosAcademicosSeeder::class,
            SeccionesSeeder::class,

            // Nivel 3: Asignaciones y Vínculos Operativos
            ProfesoresSeeder::class,
            ProfesorSeccionSeeder::class, // Pobla la tabla pivot N:M
            PersonasSeeder::class,
        ]);
    }
}