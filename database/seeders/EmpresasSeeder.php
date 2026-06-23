<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresasSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = [
            "CVG ALCASA", 
            "CVG BAUXILUM", 
            "CVG BRIQCAR/VENPRECAR", 
            "CVG BRIQUETERA DEL ORINOCO/ORINOCO IRON", 
            "CVG BRIQVEN/MATESI",   
            "CVG CABELUM",    
            "CVG CARBONORCA",    
            "CVG CASA MATRIZ",    
            "CVG COMSIGUA",   
            "CVG FERROCASA",    
            "CVG FERROMINERA DEL ORINOCO",    
            "CVG LOGÍSTICA",   
            "CVG MINERVEN",   
            "CVG SIDOR",   
            "CVG VENALUM"
        ];

        foreach ($empresas as $nombreEmpresa) {
            DB::table('empresas')->updateOrInsert(
                ['nombre_empresa' => $nombreEmpresa]
            );
        }
    }
}