<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstatusExpediente extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'estatus_expedientes';
    protected $primaryKey = 'id_estatus_expediente';

    // Desactivar timestamps por ser catálogo (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'nombre_estatus_expediente'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Un estatus puede estar asignado a muchas titulaciones de personas
    public function titulaciones(): HasMany
    {
        return $this->hasMany(TitulacionPersona::class, 'id_estatus_expediente', 'id_estatus_expediente');
    }
}