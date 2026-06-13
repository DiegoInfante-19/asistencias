<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cohorte extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'cohortes';
    protected $primaryKey = 'id_cohortes';

    // Desactivar timestamps si no los incluiste en la migración de cohortes (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'numero_cohorte',
        'fecha_inicio_cohorte',
        'fecha_fin_cohorte',
        'descripcion_cohorte',
        'estatus_cohorte'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Una cohorte tiene muchas inscripciones de participantes
    public function inscripciones()
    {
        return $this->hasMany(InscripcionCohorte::class, 'id_cohortes', 'id_cohortes');
    }

    // Una cohorte tiene muchas sesiones académicas
    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'id_cohortes', 'id_cohortes');
    }
}