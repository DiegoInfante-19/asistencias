<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // 1. RELACIÓN ACTUALIZADA: De HasMany a BelongsToMany
    public function secciones(): BelongsToMany
    {
        return $this->belongsToMany(Seccion::class, 'pnf_seccion', 'id_pnf', 'id_seccion')
                    ->withTimestamps();
    }

    // Un PNF tiene asignados varios profesores
    public function profesores(): HasMany 
    {
        return $this->hasMany(Profesor::class, 'id_pnf', 'id_pnf');
    }
}