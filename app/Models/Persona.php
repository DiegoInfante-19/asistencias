<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute; 
use Illuminate\Support\Str; 

class Persona extends Model
{
    use SoftDeletes;

    protected $table = 'personas';
    protected $primaryKey = 'id_personas';

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
        'id_cohortes',
        'continua_estudios'
    ];

    /**
     * ==========================================
     * MUTADORES (Fuerzan las mayúsculas a nivel de BD)
     * ==========================================
     */
    protected function primerNombrePersonas(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : null,
        );
    }

    protected function segundoNombrePersonas(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : null,
        );
    }

    protected function primerApellidoPersonas(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : null,
        );
    }

    protected function segundoApellidoPersonas(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Str::upper($value) : null,
        );
    }

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

    public function titulacionPersonas(): HasMany
    {
        return $this->hasMany(TitulacionPersona::class, 'id_personas', 'id_personas');
    }

    public function titulacionPersona(): HasOne
    {
        return $this->hasOne(TitulacionPersona::class, 'id_personas', 'id_personas')->latestOfMany('id_titulacion_personas');
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
        return $this->hasOne(InscripcionSeccion::class, 'id_personas', 'id_personas')->latestOfMany('id_inscripcion_seccion');
    }

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

    public function getFechaNacimientoFormateadaAttribute()
    {
        return $this->fecha_nacimiento_personas 
            ? \Carbon\Carbon::parse($this->fecha_nacimiento_personas)->format('d/m/Y') 
            : 'No registrada';
    }

    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento_personas 
            ? \Carbon\Carbon::parse($this->fecha_nacimiento_personas)->age . ' años' 
            : 'Edad desconocida';
    }

    public function getNombreCortoAttribute()
    {
        return trim("{$this->primer_nombre_personas} {$this->primer_apellido_personas}");
    }

    public function getTituloBaseAttribute()
    {
        $titulacion = $this->titulacionPersona;
        if (!$titulacion) return 'SIN TÍTULO';

        return $titulacion->titulacion->nombre_titulo_base ?? 'TÍTULO NO ESPECIFICADO';
    }

    public function getPuedeContinuarAttribute(): bool
    {
        if ($this->continua_estudios === 0 || $this->continua_estudios === false || strtolower((string)$this->continua_estudios) === 'no') {
            return false;
        }
        return true;
    }

    /**
     * ==========================================
     * HELPERS DE NEGOCIO (Control de Estudios)
     * ==========================================
     */
    
    /**
     * Retorna TRUE si el estudiante ya posee una inscripción en estatus 'Activo'.
     * Este será el candado maestro para evitar dobles inscripciones.
     */
    public function tieneInscripcionActiva(): bool
    {
        return $this->inscripcionesSecciones()
                    ->where('estatus_inscripcion', 'Activo')
                    ->whereNull('deleted_at')
                    ->exists();
    }
}