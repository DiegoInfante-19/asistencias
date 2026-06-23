<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatusExpedientesSeeder extends Seeder
{
    public function run(): void
    {
        $estatus = [
            "RECIBIDO",
            "EN ESPERA DEL PORTAFOLIO",
            "ESPERANDO UNA INFORMACION POR PARTE DE CVG"
        ];

        foreach ($estatus as $nombre) {
            DB::table('estatus_expedientes')->updateOrInsert(
                ['nombre_estatus_expediente' => $nombre]
            );
        }
    }
}