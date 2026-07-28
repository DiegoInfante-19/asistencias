<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\EstadoAsistencia; // IMPORTANTE: Importar el Enum

class Asistencia extends Model
{
    use SoftDeletes;

    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencias';

    protected $fillable = [
        'id_sesiones',
        'id_inscripcion_cohortes',
        'estado_asistencia',
        'observacion_asistencia'
    ];

    // Casteo automático del campo hacia la clase Enum
    protected $casts = [
        'estado_asistencia' => EstadoAsistencia::class,
    ];

    /**
     * RELACIONES
     */

    public function sesion()
    {
        return $this->belongsTo(Sesion::class, 'id_sesiones', 'id_sesiones');
    }

    // CORREGIDO: Cambiamos Jiang() por el nombre de relación estándar y profesional
    public function inscripcionCohorte()
    {
        return $this->belongsTo(InscripcionCohorte::class, 'id_inscripcion_cohortes', 'id_inscripcion_cohortes');
    }
}