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
        'descripcion_cohorte',
        'estatus_cohorte'
    ];

    /**
     * RELACIONES
     */
    public function periodosAcademicos(): HasMany
    {
        return $this->hasMany(PeriodoAcademico::class, 'id_cohortes', 'id_cohortes');
    }
}