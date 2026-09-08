<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodoAcademico extends Model
{
    protected $table = 'periodos_academicos';
    protected $primaryKey = 'id_periodo';
    
    protected $fillable = [
        'id_cohortes', 
        'fecha_inicio', 
        'fecha_fin', 
        'estatus_periodo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function cohorte(): BelongsTo
    {
        return $this->belongsTo(Cohorte::class, 'id_cohortes', 'id_cohortes');
    }

    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class, 'id_periodo', 'id_periodo');
    }

    /**
     * Eventos del modelo PeriodoAcademico.
     */
    protected static function booted()
    {
        static::updated(function ($periodo) {
            // Verificamos si el estatus del período cambió
            if ($periodo->isDirty('estatus_periodo')) {
                
                // Mapeo: 'Activo' -> 'Activa', cualquier otro ('Finalizada', etc.) -> 'Inactiva'
                $estatusSeccion = $periodo->estatus_periodo === 'Activo' ? 'Activa' : 'Inactiva';

                // Instanciamos cada sección y la actualizamos
                foreach ($periodo->secciones as $seccion) {
                    $seccion->update([
                        'estatus_seccion' => $estatusSeccion
                    ]);
                }
            }
        });
    }
}