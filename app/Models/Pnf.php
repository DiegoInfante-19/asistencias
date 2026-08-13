<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pnf extends Model 
{
    protected $table = 'pnfs';
    protected $primaryKey = 'id_pnf';

    protected $fillable = [
        'nombre_pnf',
        'descripcion_pnf',
        'vigencia_pnf'
    ];

    protected $casts = [
        'vigencia_pnf' => 'boolean',
    ];

    /**
     * RELACIONES
     */

    public function acreditaciones(): HasMany 
    {
        return $this->hasMany(Acreditacion::class, 'id_pnf', 'id_pnf');
    }

    public function empresasPnf(): HasMany 
    {
        return $this->hasMany(EmpresaPnf::class, 'id_pnf', 'id_pnf');
    }

    public function titulosPnf(): HasMany 
    {
        return $this->hasMany(TituloPnf::class, 'id_pnf', 'id_pnf');
    }

    // Un PNF tiene varios grupos académicos (TSU, Ingeniería en distintas cohortes)
    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class, 'id_pnf', 'id_pnf');
    }

    // Un PNF tiene asignados varios profesores
    public function profesores(): HasMany 
    {
        return $this->hasMany(Profesor::class, 'id_pnf', 'id_pnf');
    }
}