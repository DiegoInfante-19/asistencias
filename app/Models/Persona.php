<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // ¡Nuevo Import!

class Persona extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria
    protected $table = 'personas';
    protected $primaryKey = 'id_personas';

    // Seguridad de Asignación Masiva
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
     * ==========================================
     * RELACIONES DEL NÚCLEO
     * ==========================================
     */

    // Pertenece a un lugar de nacimiento (1 a 1 Inverso)
    public function lugarNacimiento(): BelongsTo
    {
        return $this->belongsTo(LugarNacimientoPersona::class, 'id_lugar_nacimiento', 'id_lugar_nacimiento');
    }

    // Una persona tiene muchos teléfonos (1 a Muchos)
    public function telefonos(): HasMany
    {
        return $this->hasMany(TelefonoPersona::class, 'id_personas', 'id_personas');
    }

    /**
     * ==========================================
     * RELACIONES DE REGLA 1 A 1
     * ==========================================
     */

    // Observación única (Corregido de HasMany a HasOne)
    public function observacion(): HasOne
    {
        return $this->hasOne(ObservacionPersona::class, 'id_personas', 'id_personas');
    }

    // Perfil Laboral / Empresa actual (1 a 1)
    public function empresaPersona(): HasOne
    {
        return $this->hasOne(EmpresaPersona::class, 'id_personas', 'id_personas');
    }

    // Expediente de Titulación Actual (1 a 1)
    public function titulacionPersona(): HasOne
    {
        return $this->hasOne(TitulacionPersona::class, 'id_personas', 'id_personas');
    }

    /**
     * ==========================================
     * RELACIONES DE HISTORIAL (1 a Muchos)
     * ==========================================
     */

    // Títulos Previos / Formación de Ingreso (1 a Muchos)
    public function formacionAcademica(): HasMany
    {
        return $this->hasMany(PersonaFormacionAcademica::class, 'id_personas', 'id_personas');
    }

    // Acreditaciones (1 a Muchos)
    public function acreditaciones(): HasMany
    {
        return $this->hasMany(Acreditacion::class, 'id_personas', 'id_personas');
    }

    // Historial completo de Inscripciones a lo largo de los años
    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionSeccion::class, 'id_personas', 'id_personas');
    }

    public function inscripcionActual(): HasOne
    {
        return $this->hasOne(InscripcionSeccion::class, 'id_personas', 'id_personas')->latestOfMany();
    }

    /**
     * ==========================================
     * ACCESSORS (Atributos Virtuales)
     * ==========================================
     */

    // Permite usar $persona->nombre_completo en las vistas
    public function getNombreCompletoAttribute()
    {
        // SE AGREGARON LOS SÍMBOLOS $ FALTANTES
        $nombres = trim("{$this->primer_nombre_personas} {$this->segundo_nombre_personas}");
        $apellidos = trim("{$this->primer_apellido_personas} {$this->segundo_apellido_personas}");
        
        return preg_replace('/\s+/', ' ', "{$nombres} {$apellidos}");
    }
}
