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
            // Nivel 0: Catálogos Base
            RoleSeeder::class,
            CargosSeeder::class,
            EstadosSeeder::class,
            TitulosSeeder::class,
            EstatusExpedientesSeeder::class,
            PnfsSeeder::class,
            EmpresasSeeder::class,
            PeriodoRecesosSeeder::class,
            CohorteSeeder::class,

            // Nivel 1: Entidades con dependencias directas
            CiudadesSeeder::class,
            TitulosPnfSeeder::class,
            UsersSeeder::class,
            EmpresaPnfSeeder::class,

            // Nivel 2: Generación automática de Grupos Académicos
            GrupoAcademicoSeeder::class,

            // Nivel 3: Asignación y vinculación de Profesores
            ProfesoresSeeder::class,
            PersonasSeeder::class,
        ]);
    }
}