<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'estados';
    protected $primaryKey = 'id_estado';

    // Desactivar timestamps por ser catálogo (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'nombre_estado'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Un estado tiene muchas ciudades
    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'id_estado', 'id_estado');
    }
}