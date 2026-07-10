<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LugarNacimientoPersona extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'lugar_nacimiento_personas';
    protected $primaryKey = 'id_lugar_nacimiento';
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_estado',
        'id_ciudad',
        'detalles_adicionales'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // El lugar de nacimiento pertenece a un estado
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    // El lugar de nacimiento pertenece a una ciudad
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    // Un lugar de nacimiento puede estar asociado a muchas personas
    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class, 'id_lugar_nacimiento', 'id_lugar_nacimiento');
    }

    // Permite usar $lugar->direccion_completa en las vistas
    public function getDireccionCompletaAttribute()
    {
        $direccion = "{$this->ciudad->nombre_ciudad}, {$this->estado->nombre_estado}";
        
        if (!empty($this->detalles_adicionales)) {
            $direccion .= " - Detalles: {$this->detalles_adicionales}";
        }
        
        return $direccion;
    }
}