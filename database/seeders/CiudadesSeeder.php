<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CiudadesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Diccionario de Ciudades agrupadas por el nombre exacto del Estado
        $ciudadesPorEstado = [
            'Amazonas'         => ['Puerto Ayacucho', 'San Fernando de Atabapo'],
            'Anzoátegui'       => ['Anaco', 'Barcelona', 'El Tigre', 'Lechería', 'Puerto La Cruz', 'Soledad'],
            'Apure'            => ['Guasdualito', 'San Fernando de Apure'],
            'Aragua'           => ['La Victoria', 'Maracay', 'Turmero'],
            'Barinas'          => ['Barinas', 'Socopó'],
            'Bolívar'          => ['Ciudad Bolívar', 'Ciudad Guayana', 'El Callao', 'El Manteco', 'El Palmar', 'El Pao', 'Santa Rosalía', 'Tumeremo', 'Upata'],
            'Carabobo'         => ['Guacara', 'Puerto Cabello', 'Valencia'],
            'Cojedes'          => ['San Carlos', 'Tinaquillo'],
            'Delta Amacuro'    => ['Tucupita'],
            'Distrito Capital' => ['Caracas'],
            'Falcón'           => ['Coro', 'Punto Fijo'],
            'Guárico'          => ['Calabozo', 'San Juan de los Morros', 'Valle de la Pascua'],
            'La Guaira'        => ['La Guaira', 'Maiquetía'],
            'Lara'             => ['Barquisimeto', 'Carora'],
            'Mérida'           => ['El Vigía', 'Mérida'],
            'Miranda'          => ['Guarenas', 'Guatire', 'Guaicaipuro (Los Teques)', 'Los Teques', 'Petare', 'Río Chico'],
            'Monagas'          => ['Barrancas del Orinoco', 'Caripito', 'Maturín', 'Punta de Mata', 'Temblador', 'Viento Fresco'],
            'Nueva Esparta'    => ['La Asunción', 'Pampatar', 'Porlamar'],
            'Portuguesa'       => ['Acarigua', 'Guanare'],
            'Sucre'            => ['Carúpano', 'Cumaná', 'Güiria', 'Irapa', 'Los Pozotes', 'Mundo Nuevo'],
            'Táchira'          => ['Queniquea', 'San Antonio del Táchira', 'San Cristóbal'],
            'Trujillo'         => ['Betijoque', 'Trujillo', 'Valera'],
            'Yaracuy'          => ['San Felipe', 'Yaritagua'],
            'Zulia'            => ['Cabimas', 'Ciudad Ojeda', 'Maracaibo']
        ];

        // 2. Extraer todos los estados de la DB y crear un mapa [ 'Nombre' => id_estado ]
        // Esto evita hacer decenas de consultas SELECT durante el bucle.
        $estadosDB = DB::table('estados')->pluck('id_estado', 'nombre_estado');

        // 3. Preparar la inserción
        foreach ($ciudadesPorEstado as $nombreEstado => $ciudades) {
            
            // Verificamos que el estado exista en la base de datos para evitar errores
            if (!isset($estadosDB[$nombreEstado])) {
                continue; 
            }

            $idEstado = $estadosDB[$nombreEstado];

            foreach ($ciudades as $ciudad) {
                // Usamos updateOrInsert evaluando ambas columnas para evitar duplicados, 
                // ya que pueden existir ciudades con el mismo nombre en distintos estados.
                DB::table('ciudades')->updateOrInsert(
                    [
                        'id_estado' => $idEstado,
                        'nombre_ciudad' => $ciudad
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }
    }
}