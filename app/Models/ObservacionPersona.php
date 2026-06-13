<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservacionPersona extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'observacion_personas';
    protected $primaryKey = 'id_observacion_personas';

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_personas', 
        'observacion_personas'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Una observación pertenece a una persona específica
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }
}