<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException; 

class InscripcionSeccion extends Model
{
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
     * HOOK DE ARQUITECTURA - SALVADO Y ADAPTADO
     */
    protected static function booted()
    {
        static::creating(function ($inscripcion) {
            static::validarIntegridadAcademica($inscripcion);
        });

        static::updating(function ($inscripcion) {
            if ($inscripcion->isDirty('id_seccion') || $inscripcion->isDirty('id_personas')) {
                static::validarIntegridadAcademica($inscripcion);
            }
        });
    }

    protected static function validarIntegridadAcademica($inscripcion)
    {
        $expediente = TitulacionPersona::where('id_personas', $inscripcion->id_personas)->first();
        
        // FALTABA EL SÍMBOLO $ EN LAS DOS VARIABLES DE ESTA LÍNEA
        $seccion = Seccion::find($inscripcion->id_seccion); 

        if (!$expediente) {
            throw ValidationException::withMessages([
                'seguridad_nucleo' => 'Violación de Dominio: Imposible registrar la inscripción. El estudiante no posee un Expediente Académico (PNF) definido.'
            ]);
        }

        // FALTABA EL SÍMBOLO $ EN LA VARIABLE $seccion AQUÍ TAMBIÉN
        if ($seccion && $expediente->id_pnf !== $seccion->id_pnf) {
            throw ValidationException::withMessages([
                'seguridad_nucleo' => sprintf(
                    'Violación de Dominio: Inconsistencia grave. La sección asignada pertenece al PNF ID [%s], pero el expediente del estudiante dicta el PNF ID [%s].',
                    $seccion->id_pnf,
                    $expediente->id_pnf
                )
            ]);
        }
    }

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