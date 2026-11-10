<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CalculaMetricasAsistencia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException; 

class InscripcionSeccion extends Model
{
    use CalculaMetricasAsistencia;
    use SoftDeletes;

    protected $table = 'inscripciones_secciones';
    protected $primaryKey = 'id_inscripcion_seccion';

    protected $fillable = [
        'id_personas',
        'id_seccion',
        'fecha_inscripcion',
        'estatus_inscripcion' 
    ];

    protected $casts = [
        'fecha_inscripcion' => 'date',
    ];

    /**
     * ==========================================================
     * HOOK DE ARQUITECTURA - BLINDAJE DE NEGOCIO MULTI-PNF
     * ==========================================================
     */
    protected static function booted()
    {
        static::creating(function ($inscripcion) {
            static::validarIntegridadAcademica($inscripcion);
            static::validarInscripcionActivaUnica($inscripcion);
        });

        static::updating(function ($inscripcion) {
            if ($inscripcion->isDirty('id_seccion') || $inscripcion->isDirty('id_personas') || $inscripcion->isDirty('estatus_inscripcion')) {
                static::validarIntegridadAcademica($inscripcion);
                static::validarInscripcionActivaUnica($inscripcion);
            }
        });
    }

    /**
     * REGLA 1: Valida que el PNF del estudiante figure entre los permitidos por la sección
     */
    protected static function validarIntegridadAcademica($inscripcion)
    {
        $expediente = TitulacionPersona::where('id_personas', $inscripcion->id_personas)->first();
        $seccion = Seccion::with('pnfs')->find($inscripcion->id_seccion); 

        if (!$expediente) {
            throw ValidationException::withMessages([
                'seguridad_nucleo' => 'Violación de Dominio: Imposible registrar la inscripción. El estudiante no posee un Expediente Académico (PNF) definido.'
            ]);
        }

        if ($seccion && !$seccion->pnfs->contains('id_pnf', $expediente->id_pnf)) {
            throw ValidationException::withMessages([
                'seguridad_nucleo' => sprintf(
                    'Violación de Dominio: Inconsistencia grave. La sección asignada no admite el PNF ID [%s] del estudiante.',
                    $expediente->id_pnf
                )
            ]);
        }
    }

    /**
     * REGLA 2: Bloquea si la persona intenta tener 2 o más inscripciones "Activas"
     */
    protected static function validarInscripcionActivaUnica($inscripcion)
    {
        // Solo validamos unicidad si estamos intentando guardar un estatus 'Activo'
        if ($inscripcion->estatus_inscripcion === 'Activo') {
            $existeOtraActiva = static::where('id_personas', $inscripcion->id_personas)
                ->where('estatus_inscripcion', 'Activo')
                ->whereNull('deleted_at')
                // Si es una actualización, ignoramos el ID del registro actual para no autobloquearnos
                ->when($inscripcion->id_inscripcion_seccion, function ($query, $id) {
                    $query->where('id_inscripcion_seccion', '!=', $id);
                })
                ->exists();

            if ($existeOtraActiva) {
                throw ValidationException::withMessages([
                    'id_personas' => 'Bloqueo a nivel de Modelo: El estudiante ya se encuentra activo en otra sección. Violación de restricción única de matrícula.'
                ]);
            }
        }
    }

    /**
     * ==========================================================
     * RELACIONES
     * ==========================================================
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'id_seccion', 'id_seccion');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_inscripcion_seccion', 'id_inscripcion_seccion');
    }
}