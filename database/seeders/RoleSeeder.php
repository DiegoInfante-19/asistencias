<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Role; // Asegúrate de usar TU modelo

class RoleSeeder extends Seeder{
    public function run(): void{
        $roles = [
            [
                'nombre' => 'Tecnico',
                'descripcion' => 'Programador y soporte del sistema'
            ],
            [
                'nombre' => 'Admin',
                'descripcion' => 'Secretaria y personal administrativo'
            ],
            [
                'nombre' => 'Profesor',
                'descripcion' => 'Personal docente del Vicerrectorado'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}