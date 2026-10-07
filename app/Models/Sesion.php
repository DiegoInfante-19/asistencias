<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
        'id_profesor'  => 'integer', // <--- Soluciona el desfase de tipos
        'id_seccion'   => 'integer', // <--- Recomendado para mantener integridad
    ];

    public function tieneAsistenciaRegistrada(): bool
    {
        return $this->asistencias()->count() > 0;
    }

    // ==========================================
    // LÓGICA DE NEGOCIO: Límite de Edición
    // ==========================================

    public function limiteEdicion(): Carbon
    {
        // El límite matemático es 48 horas (2 días) después de la fecha de la sesión
        return Carbon::parse($this->fecha_sesion)->addHours(48);
    }

    /**
     * Indica si la ventana matemática de 48 horas ya se cerró.
     * (Independientemente del usuario).
     */
    public function tiempoAgotado(): bool
    {
        return now()->greaterThan($this->limiteEdicion());
    }

    /**
     * Alias por compatibilidad con SesionesDataTable
     */
    public function estaCerrada(): bool
    {
        return $this->tiempoAgotado();
    }

    /**
     * Devuelve las horas que faltan para que se cierre la ventana de 48 horas.
     * Si ya pasó, devuelve 0.
     */
    public function horasRestantesEdicion(): int
    {
        $limite = $this->limiteEdicion();
        $ahora = now();

        return $ahora->lessThan($limite) ? (int) $ahora->diffInHours($limite) : 0;
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
}