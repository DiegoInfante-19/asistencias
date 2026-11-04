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
    
    // 1. ELIMINAMOS 'id_pnf' del fillable
    protected $fillable = [
        'id_periodo', 
        'nombre_seccion', 
        'estatus_seccion'
    ];

    public function periodoAcademico(): BelongsTo
    {
        return $this->belongsTo(PeriodoAcademico::class, 'id_periodo', 'id_periodo');
    }

    // 2. NUEVA RELACIÓN MUCHOS A MUCHOS
    public function pnfs(): BelongsToMany
    {
        return $this->belongsToMany(Pnf::class, 'pnf_seccion', 'id_seccion', 'id_pnf')
                    ->withTimestamps();
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
     * MÉTODOS DE AYUDA Y ESTANDARIZACIÓN DE ESTATUS
     * =====================================================================
     */

    public function estaActiva(): bool
    {
        return in_array(strtolower($this->estatus_seccion), ['activo', 'activa'], true);
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->whereIn('estatus_seccion', ['Activo', 'Activa', 'activo', 'activa']);
    }

    public function scopeActivasParaAsignacion(Builder $query): Builder
    {
        return $query->whereIn('estatus_seccion', ['Activo', 'Activa', 'activo', 'activa'])
            ->whereHas('periodoAcademico', function ($q) {
                $q->whereIn('estatus_periodo', ['Activo', 'Activa', 'activo', 'activa']);
            });
    }

    // 3. SCOPE ACTUALIZADO PARA BUSCAR EN LA TABLA PIVOTE
    public function scopePorPnf(Builder $query, $idPnf): Builder
    {
        return $query->whereHas('pnfs', function (Builder $q) use ($idPnf) {
            $q->where('pnfs.id_pnf', $idPnf);
        });
    }

    // 4. NUEVO HELPER: Verifica si la sección admite a un PNF
    public function admitePnf(int $idPnf): bool
    {
        return $this->pnfs()->where('pnfs.id_pnf', $idPnf)->exists();
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

    // 5. ACCESOR ACTUALIZADO: Une los nombres de múltiples PNFs con coma
    public function getNombreCompletoSelectAttribute(): string
    {
        $pnfsNombres = $this->pnfs->pluck('nombre_pnf')->implode(', ');
        $pnfTexto = $pnfsNombres ?: 'Sin PNF asignado';
        
        $cohorteNum = $this->periodoAcademico->cohorte->numero_cohorte ?? 'S/C';
        $periodoFechas = '';

        if ($this->periodoAcademico) {
            $inicio = $this->periodoAcademico->fecha_inicio ? $this->periodoAcademico->fecha_inicio->format('Y') : '';
            $periodoFechas = $inicio ? "({$inicio})" : '';
        }

        return "{$this->nombre_seccion} — [PNF: {$pnfTexto} | Cohorte: {$cohorteNum} {$periodoFechas}]";
    }
}