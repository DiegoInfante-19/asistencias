<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pnf extends Model{

    protected $table = 'pnfs';
    protected $primaryKey = 'id_pnf';

    protected $fillable = [
        'nombre_pnf',
        'descripcion_pnf',
        'vigencia_pnf'
    ];

    // Aseguramos que Laravel trate la vigencia estrictamente como true/false
    protected $casts = [
        'vigencia_pnf' => 'boolean',
    ];

    /*
     * RELACIONES (Paso 6)
     */

    public function acreditaciones(): HasMany{
        return $this->hasMany(Acreditacion::class, 'id_pnf', 'id_pnf');
    }

    // Trae todas las vinculaciones con empresas
    public function empresasPnf(): HasMany{
        return $this->hasMany(EmpresaPnf::class, 'id_pnf', 'id_pnf');
    }

    // Trae todas las vinculaciones con títulos
    public function titulosPnf(): HasMany{
        return $this->hasMany(TituloPnf::class, 'id_pnf', 'id_pnf');
    }
}