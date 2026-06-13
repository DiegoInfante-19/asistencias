<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persona extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'personas';
    protected $primaryKey = 'id_personas';
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'cedula_personas',
        'primer_nombre_personas',
        'segundo_nombre_personas',
        'primer_apellido_personas',
        'segundo_apellido_personas',
        'sexo_personas',
        'fecha_nacimiento_personas',
        'id_lugar_nacimiento',
        'email_personas'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Pertenece a un lugar de nacimiento
    public function lugarNacimiento(): BelongsTo
    {
        return $this->belongsTo(LugarNacimientoPersona::class, 'id_lugar_nacimiento', 'id_lugar_nacimiento');
    }

    // Una persona tiene muchos teléfonos
    public function telefonos(): HasMany
    {
        return $this->hasMany(TelefonoPersona::class, 'id_personas', 'id_personas');
    }

    // Una persona tiene muchas acreditaciones
    public function acreditaciones(): HasMany
    {
        return $this->hasMany(Acreditacion::class, 'id_personas', 'id_personas');
    }

    // Una persona tiene muchas inscripciones en cohortes
    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionCohorte::class, 'id_personas', 'id_personas');
    }

    // Una persona tiene muchas observaciones en su expediente
    public function observaciones(): HasMany
    {
        return $this->hasMany(ObservacionPersona::class, 'id_personas', 'id_personas');
    }
}