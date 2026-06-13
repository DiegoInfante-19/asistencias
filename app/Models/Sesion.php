<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sesion extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'sesiones';
    protected $primaryKey = 'id_sesiones';

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_cohortes', 
        'id_profesor', 
        'fecha_sesion', 
        'observacion_sesion'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Una sesión pertenece a una cohorte
    public function cohorte(): BelongsTo
    {
        return $this->belongsTo(Cohorte::class, 'id_cohortes', 'id_cohortes');
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