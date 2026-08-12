<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException; // <-- Importación obligatoria para el Fail-Safe

class InscripcionCohorte extends Model
{
    use SoftDeletes;

    protected $table = 'inscripcion_cohortes';
    protected $primaryKey = 'id_inscripcion_cohortes';

    protected $fillable = [
        'id_personas',
        'id_grupo',
        'fecha_inscripcion',
        'estatus_inscripcion_cohortes'
    ];

    /**
     * HOOK DE ARQUITECTURA (Ciclo de Vida de Eloquent)
     * Se ejecuta de forma automática antes de que el motor de Base de Datos procese la transacción.
     */
    protected static function booted()
    {
        // 1. Intercepción antes de crear un nuevo registro (INSERT)
        static::creating(function ($inscripcion) {
            static::validarIntegridadAcademica($inscripcion);
        });

        // 2. Intercepción antes de actualizar un registro existente (UPDATE)
        static::updating(function ($inscripcion) {
            // Evaluamos la regla solo si están intentando cambiar el grupo o el estudiante
            if ($inscripcion->isDirty('id_grupo') || $inscripcion->isDirty('id_personas')) {
                static::validarIntegridadAcademica($inscripcion);
            }
        });
    }

    /**
     * Lógica Centralizada de Validación de Dominio (Fail-Safe)
     */
    protected static function validarIntegridadAcademica($inscripcion)
    {
        // Traemos las entidades relacionadas sin usar relaciones para evitar dependencias circulares en el boot
        $expediente = TitulacionPersona::where('id_personas', $inscripcion->id_personas)->first();
        $grupo = GrupoAcademico::find($inscripcion->id_grupo);

        // Regla 1: Barrera de existencia de expediente
        if (!$expediente) {
            throw ValidationException::withMessages([
                'seguridad_nucleo' => 'Violación de Dominio (Capa Modelo): Imposible registrar la inscripción. El estudiante no posee un Expediente Académico (PNF) definido en el sistema.'
            ]);
        }

        // Regla 2: Barrera de coherencia cruzada
        if ($grupo && $expediente->id_pnf !== $grupo->id_pnf) {
            throw ValidationException::withMessages([
                'seguridad_nucleo' => sprintf(
                    'Violación de Dominio (Capa Modelo): Inconsistencia grave. El grupo asignado pertenece al PNF ID [%s], pero el expediente del estudiante dicta el PNF ID [%s]. Transacción abortada.',
                    $grupo->id_pnf,
                    $expediente->id_pnf
                )
            ]);
        }
    }

    /**
     * RELACIONES
     */

    // Una inscripción pertenece a una persona
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    // Una inscripción pertenece a un grupo académico
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(GrupoAcademico::class, 'id_grupo', 'id_grupo');
    }

    // Una inscripción tiene muchas asistencias registradas
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_inscripcion_cohortes', 'id_inscripcion_cohortes');
    }
}