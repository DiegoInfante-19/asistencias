<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * RELACIONES
     */

    // Una inscripción pertenece a una persona
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    // CORREGIDO: Una inscripción pertenece a un grupo académico
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