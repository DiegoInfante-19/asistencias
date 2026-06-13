<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencia extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencias';

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_sesiones',
        'id_inscripcion_cohortes',
        'estado_asistencia',
        'observacion_asistencia'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Una asistencia pertenece a una sesión específica
    public function sesion()
    {
        return $this->belongsTo(Sesion::class, 'id_sesiones', 'id_sesiones');
    }

    // Una asistencia pertenece a una inscripción de cohorte
    public function inscripcion()
    {
        return $this->belongsTo(InscripcionCohorte::class, 'id_inscripcion_cohortes', 'id_inscripcion_cohortes');
    }
}