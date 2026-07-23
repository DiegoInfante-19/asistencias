<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LugarNacimientoPersona extends Model
{
    protected $table = 'lugar_nacimiento_personas';
    protected $primaryKey = 'id_lugar_nacimiento';

    // CORREGIDO: Se elimina 'id_estado' para evitar escrituras masivas fantasma
    protected $fillable = [
        'id_ciudad',
        'detalles_adicionales'
    ];

    /**
     * RELACIONES REFACTORIZADAS
     */

    // CORREGIDO: El estado ahora se obtiene de forma indirecta cruzando con la ciudad
    public function estado()
    {
        // Si existe la ciudad cargada, accedemos a su relación estado.
        // Nota: Asegúrate de que el modelo Ciudad tenga definida la relación 'estado'.
        return $this->ciudad ? $this->ciudad->belongsTo(Estado::class, 'id_estado', 'id_estado') : null;
    }

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class, 'id_lugar_nacimiento', 'id_lugar_nacimiento');
    }

    /**
     * ACCESSORS
     */
    public function getDireccionCompletaAttribute()
    {
        // Buscamos el nombre de la ciudad de la relación directa
        $ciudad = $this->ciudad ? $this->ciudad->nombre_ciudad : 'Ciudad desconocida';
        
        // CORREGIDO: Buscamos el estado cruzando de forma segura a través del modelo Ciudad
        $estado = ($this->ciudad && $this->ciudad->estado) ? $this->ciudad->estado->nombre_estado : 'Estado desconocido';
        
        $direccion = "{$ciudad}, {$estado}";
        return $direccion;
    }
}