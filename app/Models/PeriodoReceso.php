<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoReceso extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'periodo_recesos';
    protected $primaryKey = 'id_periodo_receso';

    // Desactivar timestamps por ser catálogo (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'nombre_periodo_receso',
        'fecha_inicio_periodo_receso',
        'fecha_fin_periodo_receso',
        'descripcion_periodo_receso',
        'nivel_periodo_receso',
        'suspension_actividades'
    ];

    protected $casts = [
        'fecha_inicio_periodo_receso' => 'date',
        'fecha_fin_periodo_receso'    => 'date',
        'suspension_actividades'      => 'boolean',
    ];

}