<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoReceso extends Model
{
    protected $table = 'periodo_recesos';
    protected $primaryKey = 'id_periodo_receso';
    public $timestamps = false;
    
    protected $fillable = [
        'nombre_periodo_receso',
        'fecha_inicio_periodo_receso',
        'fecha_fin_periodo_receso',
        'descripcion_periodo_receso',
        'nivel_periodo_receso',
        'suspension_actividades',
        'tipo_receso' // <--- Añadido
    ];

    protected $casts = [
        'fecha_inicio_periodo_receso' => 'date',
        'fecha_fin_periodo_receso'    => 'date',
        'suspension_actividades'      => 'boolean',
    ];
}