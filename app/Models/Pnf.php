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

    /*
    
    * RELACIONES (Paso 6) *
    
    */

    public function acreditaciones(): HasMany{
        return $this->hasMany(Acreditacion::class, 'id_pnf', 'id_pnf');
    }

    public function empresas(): HasMany{
        return $this->hasMany(EmpresaPnf::class, 'id_pnf', 'id_pnf');
    }
}