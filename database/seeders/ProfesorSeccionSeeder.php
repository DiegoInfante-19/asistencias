<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profesor;
use App\Models\Seccion;

class ProfesorSeccionSeeder extends Seeder
{
    public function run(): void
    {
        $profesores = Profesor::all();
        $secciones = Seccion::all();

        if ($profesores->isEmpty() || $secciones->isEmpty()) {
            return;
        }

        foreach ($profesores as $profesor) {
            // Asignamos aleatoriamente entre 1 y 3 secciones a cada profesor (Sincronización N:M)
            $seccionesAleatorias = $secciones->random(rand(1, min(3, $secciones->count())));
            
            $profesor->secciones()->sync($seccionesAleatorias->pluck('id_seccion')->toArray());
        }
    }
}