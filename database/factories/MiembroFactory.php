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
            'nombre_apellido' => fake()->name,
            'direccion' => fake()->address,
            'telefono' => fake()->phoneNumber,
            'fecha_nacimiento' => fake()->date($format = 'Y-m-d', $max = 'now'),
            'genero' =>'MASCULINO',
            'email' => fake()->unique()->safeEmail,
            'estado' =>'1',
            'ministerio' =>'pastoral',
            'fotografia' =>'Freddy.jpg',
            'fecha_ingreso' => fake()->date($format = 'Y-m-d', $max = 'now'),
        ];
    }
}
