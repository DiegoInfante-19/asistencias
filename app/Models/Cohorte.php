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

    // RELACIÓN: Para contar todos los estudiantes inscritos en la cohorte general
    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class, 'id_cohortes', 'id_cohortes');
    }

    /**
     * Eventos del modelo Cohorte.
     */
    protected static function booted()
    {
        static::updated(function ($cohorte) {
            // Verificamos si el estatus de la cohorte cambió
            if ($cohorte->isDirty('estatus_cohorte')) {
                
                // Iteramos sobre los períodos. Al actualizar la instancia, 
                // se dispara automáticamente el evento updated de PeriodoAcademico.
                foreach ($cohorte->periodosAcademicos as $periodo) {
                    $periodo->update([
                        'estatus_periodo' => $cohorte->estatus_cohorte
                    ]);
                }
            }
        });
    }
}