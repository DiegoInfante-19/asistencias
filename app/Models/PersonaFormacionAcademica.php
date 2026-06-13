<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonaFormacionAcademica extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'persona_formacion_academica';
    protected $primaryKey = 'id_persona_formacion_academica';

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_personas', 
        'id_titulos_pnf', 
        'id_titulos', 
        'observacion_formacion_academica'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // La formación pertenece a una persona
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    // La formación pertenece a un Título-PNF específico
    public function tituloPnf(): BelongsTo
    {
        return $this->belongsTo(TituloPnf::class, 'id_titulos_pnf', 'id_titulos_pnf');
    }

    // La formación pertenece a un título base
    public function titulo(): BelongsTo
    {
        return $this->belongsTo(Titulo::class, 'id_titulos', 'id_titulos');
    }
}