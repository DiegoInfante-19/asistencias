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
        // CORRECCIÓN: Ordenamos siempre de la más reciente a la más vieja
        return $this->hasMany(Sesion::class, 'id_seccion', 'id_seccion')
                    ->orderBy('fecha_sesion', 'desc');
    }

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

    // =========================================================================
    // LOCAL SCOPES PARA FILTROS AVANZADOS (Módulo de Clases y Asistencias)
    // =========================================================================

    /**
     * Filtra las secciones que pertenecen a un PNF específico.
     */
    public function scopePorPnf($query, $idPnf)
    {
        return $query->where('id_pnf', $idPnf);
    }

    /**
     * Filtra las secciones donde un profesor específico está asignado.
     */
    public function scopePorProfesor($query, $idProfesor)
    {
        return $query->whereHas('profesores', function ($q) use ($idProfesor) {
            $q->where('profesor_seccion.id_profesor', $idProfesor); 
        });
    }

    /**
     * Filtra las secciones que contienen al menos un estudiante asociado a una empresa específica.
     */
    public function scopePorEmpresa($query, $idEmpresa)
    {
        return $query->whereHas('inscripciones.persona.empresaPersona', function ($q) use ($idEmpresa) {
            $q->where('id_empresa', $idEmpresa);
        });
    }

    /**
     * Filtra las secciones que contienen al menos un estudiante optando por un título específico.
     */
    public function scopePorTitulo($query, $idTitulo)
    {
        return $query->whereHas('inscripciones.persona.titulacionPersona', function ($q) use ($idTitulo) {
            $q->where('id_titulo', $idTitulo); 
        });
    }

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