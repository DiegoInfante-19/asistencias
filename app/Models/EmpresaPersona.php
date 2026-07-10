<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Añadido para consistencia

class EmpresaPersona extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'empresa_personas';
    protected $primaryKey = 'id_empresa_personas';

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_personas', 
        'id_empresa', 
        'id_cargo'
    ];

    /**
     * RELACIONES (Paso 6)
     * Conectamos este registro intermedio con sus padres.
     */

    // La persona dueña de este registro laboral
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    // La empresa donde trabaja o trabajó
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    // El cargo que desempeña
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'id_cargo', 'id_cargo');
    }
}