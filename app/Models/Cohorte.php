<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cohorte extends Model
{
    protected $table = 'cohortes';
    protected $primaryKey = 'id_cohortes';

    public $timestamps = false;
    
    protected $fillable = [
        'numero_cohorte',
        'fecha_inicio_cohorte',
        'fecha_fin_cohorte',
        'descripcion_cohorte',
        'estatus_cohorte'
    ];

    /**
     * RELACIONES
     */

    // Una cohorte se subdivide en grupos académicos (por PNF y Nivel)
    public function gruposAcademicos(): HasMany
    {
        return $this->hasMany(GrupoAcademico::class, 'id_cohortes', 'id_cohortes');
    }
}