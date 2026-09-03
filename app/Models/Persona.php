<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; 

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
        'email_personas',
        'id_cohortes'
    ];

    /**
     * ==========================================
     * RELACIONES DEL NÚCLEO
     * ==========================================
     */

    public function lugarNacimiento(): BelongsTo
    {
        return $this->belongsTo(LugarNacimientoPersona::class, 'id_lugar_nacimiento', 'id_lugar_nacimiento');
    }

    public function cohorte(): BelongsTo
    {
        return $this->belongsTo(Cohorte::class, 'id_cohortes', 'id_cohortes');
    }

    public function telefonos(): HasMany
    {
        return $this->hasMany(TelefonoPersona::class, 'id_personas', 'id_personas');
    }

    /**
     * ==========================================
     * RELACIONES DE REGLA 1 A 1
     * ==========================================
     */

    public function observacion(): HasOne
    {
        return $this->hasOne(ObservacionPersona::class, 'id_personas', 'id_personas');
    }

    public function empresaPersona(): HasOne
    {
        return $this->hasOne(EmpresaPersona::class, 'id_personas', 'id_personas');
    }

    public function titulacionPersona(): HasOne
    {
        return $this->hasOne(TitulacionPersona::class, 'id_personas', 'id_personas');
    }

    /**
     * ==========================================
     * RELACIONES DE HISTORIAL (1 a Muchos)
     * ==========================================
     */

    public function formacionAcademica(): HasMany
    {
        return $this->hasMany(PersonaFormacionAcademica::class, 'id_personas', 'id_personas');
    }

    public function acreditaciones(): HasMany
    {
        return $this->hasMany(Acreditacion::class, 'id_personas', 'id_personas');
    }

    public function inscripcionesSecciones(): HasMany
    {
        return $this->hasMany(InscripcionSeccion::class, 'id_personas', 'id_personas');
    }

    public function inscripcionActual(): HasOne
    {
        return $this->hasOne(InscripcionSeccion::class, 'id_personas', 'id_personas')->latestOfMany();
    }

    // NUEVO: Relación estricta para verificar si tiene una inscripción Activa
    public function inscripcionActiva(): HasOne
    {
        return $this->hasOne(InscripcionSeccion::class, 'id_personas', 'id_personas')
                    ->where('estatus_inscripcion', 'Activo')
                    ->latest('fecha_inscripcion');
    }

    /**
     * ==========================================
     * ACCESSORS (Atributos Virtuales)
     * ==========================================
     */

    public function getNombreCompletoAttribute()
    {
        $nombres = trim("{$this->primer_nombre_personas} {$this->segundo_nombre_personas}");
        $apellidos = trim("{$this->primer_apellido_personas} {$this->segundo_apellido_personas}");

        return preg_replace('/\s+/', ' ', "{$nombres} {$apellidos}");
    }
}