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
        return $this->hasMany(Sesion::class, 'id_seccion', 'id_seccion')
                    ->orderBy('fecha_sesion', 'desc');
    }

    /**
     * =====================================================================
     * MÉTODOS DE AYUDA Y ESTANDARIZACIÓN DE ESTATUS (FASE 1)
     * =====================================================================
     */

    /**
     * Valida de manera estricta si la sección se encuentra en estado Activo/Activa.
     */
    public function estaActiva(): bool
    {
        return in_array(strtolower($this->estatus_seccion), ['activo', 'activa'], true);
    }

    /**
     * Scope para filtrar únicamente secciones activas a nivel general.
     */
    public function scopeActivas(Builder $query): Builder
    {
        return $query->whereIn('estatus_seccion', ['Activo', 'Activa', 'activo', 'activa']);
    }

    /**
     * Scope original optimizado: Secciones activas y con período académico vigente para asignaciones.
     */
    public function scopeActivasParaAsignacion(Builder $query): Builder
    {
        return $query->whereIn('estatus_seccion', ['Activo', 'Activa', 'activo', 'activa'])
            ->whereHas('periodoAcademico', function ($q) {
                $q->whereIn('estatus_periodo', ['Activo', 'Activa', 'activo', 'activa']);
            });
    }

    public function scopePorPnf($query, $idPnf)
    {
        return $query->where('id_pnf', $idPnf);
    }

    public function scopePorProfesor($query, $idProfesor)
    {
        return $query->whereHas('profesores', function ($q) use ($idProfesor) {
            $q->where('profesor_seccion.id_profesor', $idProfesor); 
        });
    }

    public function scopePorEmpresa($query, $idEmpresa)
    {
        return $query->whereHas('inscripciones.persona.empresaPersona', function ($q) use ($idEmpresa) {
            $q->where('id_empresa', $idEmpresa);
        });
    }

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