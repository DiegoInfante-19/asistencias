<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InscripcionCohorte extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'inscripcion_cohortes';
    protected $primaryKey = 'id_inscripcion_cohortes';

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_personas',
        'id_cohortes',
        'fecha_inscripcion',
        'estatus_inscripcion_cohortes'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Una inscripción pertenece a una persona
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    // Una inscripción pertenece a una cohorte
    public function cohorte(): BelongsTo
    {
        return $this->belongsTo(Cohorte::class, 'id_cohortes', 'id_cohortes');
    }

    // Una inscripción tiene muchas asistencias registradas
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_inscripcion_cohortes', 'id_inscripcion_cohortes');
    }
}