<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'ciudades';
    protected $primaryKey = 'id_ciudad';

    // Desactivar timestamps para catálogos (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_estado',
        'nombre_ciudad'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Una ciudad pertenece a un estado
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }
}