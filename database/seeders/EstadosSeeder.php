<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder{
    public function run(): void{
        $estados = [
            "Amazonas", 
            "Anzoátegui",    
            "Apure",      
            "Aragua",                 
            "Barinas", 
            "Bolívar",  
            "Carabobo",      
            "Cojedes",    
            "Delta Amacuro",
            "Distrito Capital",       
            "Falcón", 
            "Guárico",  
            "La Guaira",     
            "Lara",       
            "Mérida",                 
            "Miranda", 
            "Monagas",  
            "Nueva Esparta", 
            "Portuguesa", 
            "Sucre",                  
            "Táchira", 
            "Trujillo", 
            "Yaracuy",       
            "Zulia",      
            // "Dependencias Federales", 
            //  enserio crees que en la cvg hay un carajo de de las dependecias federales
            
        ];

        $data = [];
        foreach ($estados as $nombre) {
            $data[] = [
                'nombre_estado' => $nombre
            ];
        }

        DB::table('estados')->insert($data);
    }
}