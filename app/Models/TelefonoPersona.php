<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelefonoPersona extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'telefonos_personas';
    protected $primaryKey = 'id_telefonos_personas';

    // Desactivar timestamps por ser catálogo/detalle (Paso 5)
    public $timestamps = false;

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_personas', 
        'numero_telefono_personas', 
        'tipo_telefono'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Un teléfono pertenece a una persona
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }
}