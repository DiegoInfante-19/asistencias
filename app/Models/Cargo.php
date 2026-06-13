<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'cargos';
    protected $primaryKey = 'id_cargo';

    // Desactivar timestamps para catálogos (Paso 5)
    public $timestamps = false;

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'descripcion_cargo'
    ];
}