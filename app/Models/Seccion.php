<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Seccion extends Model
{
    protected $table = 'secciones';
    protected $primaryKey = 'id_seccion';
    
    protected $fillable = [
        'id_periodo', 
        'id_pnf', 
        'nombre_seccion', 
        'estatus_seccion'
    ];

    /**
     * RELACIONES
     */
    public function periodoAcademico(): BelongsTo
    {
        return $this->belongsTo(PeriodoAcademico::class, 'id_periodo', 'id_periodo');
    }

    public function pnf(): BelongsTo
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }

    public function profesores(): BelongsToMany
    {
        return $this->belongsToMany(Profesor::class, 'profesor_seccion', 'id_seccion', 'id_profesor')
                    ->withTimestamps();
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionSeccion::class, 'id_seccion', 'id_seccion');
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(Sesion::class, 'id_seccion', 'id_seccion');
    }

    /**
     * FASE 1 - PASO 1.1: Query Scope para secciones activas de periodos y cohortes activos.
     */
    public function scopeActivasParaAsignacion(Builder $query): Builder
    {
        return $query->where('estatus_seccion', 'Activa')
            ->whereHas('periodoAcademico', function ($q) {
                $q->where('estatus_periodo', 'Activo')
                  ->whereHas('cohorte', function ($subQ) {
                      $subQ->where('estatus_cohorte', 'Activo');
                  });
            });
    }

    /**
     * FASE 1 - PASO 1.2: Accessor para el nombre completo enriquecido en Selects.
     * Combina la flexibilidad del nombre ingresado con el contexto de PNF, Período y Cohorte.
     */
    public function getNombreCompletoSelectAttribute(): string
    {
        $pnfNombre = $this->pnf->nombre_pnf ?? 'Sin PNF';
        $cohorteNum = $this->periodoAcademico->cohorte->numero_cohorte ?? 'S/C';
        $periodoFechas = '';

        if ($this->periodoAcademico) {
            $inicio = $this->periodoAcademico->fecha_inicio ? $this->periodoAcademico->fecha_inicio->format('Y') : '';
            $periodoFechas = $inicio ? "({$inicio})" : '';
        }

        return "{$this->nombre_seccion} — [PNF: {$pnfNombre} | Cohorte: {$cohorteNum} {$periodoFechas}]";
    }
}