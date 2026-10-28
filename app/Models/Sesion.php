<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sesion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sesiones';
    protected $primaryKey = 'id_sesiones';

    protected $fillable = [
        'id_seccion',
        'id_profesor',
        'fecha_sesion',
        'observacion_sesion'
    ];

    protected $casts = [
        'fecha_sesion' => 'datetime',
        'id_profesor'  => 'integer',
        'id_seccion'   => 'integer',
    ];

    public function tieneAsistenciaRegistrada(): bool
    {
        return $this->asistencias()->count() > 0;
    }

    // ==========================================
    // RELACIONES
    // ==========================================

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'id_seccion', 'id_seccion');
    }

    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'id_profesor', 'id_profesor');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_sesiones', 'id_sesiones');
    }

    // ==========================================
    // HELPERS DE AUTORIZACIÓN
    // ==========================================

    public function profesorPerteneceASeccion(?int $idProfesor): bool
    {
        if (!$idProfesor) {
            return false;
        }

        if ($this->relationLoaded('seccion') && $this->seccion->relationLoaded('profesores')) {
            return $this->seccion->profesores->contains('id_profesor', $idProfesor);
        }

        return $this->seccion()
            ->whereHas('profesores', function ($query) use ($idProfesor) {
                $query->where('profesor_seccion.id_profesor', $idProfesor);
            })
            ->exists();
    }
}