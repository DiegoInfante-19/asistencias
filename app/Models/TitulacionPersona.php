<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitulacionPersona extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'titulacion_personas';
    protected $primaryKey = 'id_titulacion_personas';

    // Desactivar timestamps si no son necesarios (Paso 5)
    public $timestamps = false;

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_personas', 
        'id_titulacion', 
        'id_pnf', 
        'id_estatus_expediente'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    public function titulacion(): BelongsTo
    {
        return $this->belongsTo(Titulo::class, 'id_titulacion', 'id_titulos');
    }

    public function pnf(): BelongsTo
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }

    public function estatus(): BelongsTo
    {
        return $this->belongsTo(EstatusExpediente::class, 'id_estatus_expediente', 'id_estatus_expediente');
    }
}