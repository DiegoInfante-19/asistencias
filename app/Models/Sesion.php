<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sesion extends Model
{
    use SoftDeletes;

    protected $table = 'sesiones';
    protected $primaryKey = 'id_sesiones';

    protected $fillable = [
        'id_grupo', 
        'id_profesor', 
        'fecha_sesion', 
        'observacion_sesion'
    ];

    /**
     * RELACIONES
     */

    // CORREGIDO: Una sesión pertenece a un grupo académico
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(GrupoAcademico::class, 'id_grupo', 'id_grupo');
    }

    // Una sesión es impartida por un profesor
    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'id_profesor', 'id_profesor');
    }

    // Una sesión tiene muchas asistencias registradas
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_sesiones', 'id_sesiones');
    }
}