<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //el sistema tendra tres roles Tecnico, Admin, Profesor

        //el sistema va a tener 2 roles

        //administrador

        //secretaria

        $admin = Role::create(['name' => 'admin']);
        $secretaria = Role::create(['name'=>'secretaria']);
        // $permission = Permission::create(['name' => 'edit articles']);
    }
}
