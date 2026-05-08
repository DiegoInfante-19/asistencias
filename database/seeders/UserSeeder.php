<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'      => 'Ronald', 
            'last_name' => 'Perez',    
            'cedula'    => '12345678',       
            'email'     => 'tecnico@sistema.com',
            'username'  => 'tecnicoSystem',
            'phone'     => '55598555891',
            'status'    => 'Activo',         
            'role_id'   => 1,                
            'password'  => Hash::make('12345678'), 
        ]);
    }
}