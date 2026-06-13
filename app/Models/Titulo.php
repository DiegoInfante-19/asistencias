<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Titulo extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'titulos';
    protected $primaryKey = 'id_titulos';

    // Desactivar timestamps por ser catálogo (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'nombre_titulo_base',
        'nivel_academico'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Un título base puede estar presente en muchas titulaciones de personas
    public function titulaciones(): HasMany
    {
        return $this->hasMany(TitulacionPersona::class, 'id_titulacion', 'id_titulos');
    }
}