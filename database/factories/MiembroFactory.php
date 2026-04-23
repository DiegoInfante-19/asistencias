<?php

namespace Database\Factories;

use App\Models\Miembro;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Miembro>
 */
class MiembroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_apellido' => Str::random(length: 10),
            'direccion' => Str::random(length: 50),
            'telefono' => random_int(70000000, max: 79999999),
            'fecha_nacimiento' => '1999-01-01',
            'genero' =>'MASCULINO',
            'email' =>Str::random(length: 10).'@gmail.com',
            'estado' =>'1',
            'ministerio' =>'pastoral',
            'fotografia' =>'Freddy.jpg',
            'fecha_ingreso' =>'1999-01-01',
        ];
    }
}
