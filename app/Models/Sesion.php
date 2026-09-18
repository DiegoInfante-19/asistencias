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
    ];

    // ==========================================
    // LÓGICA DE NEGOCIO: Límite de Edición
    // ==========================================
    
    public function limiteEdicion(): Carbon
    {
        // El límite es 48 horas después de la fecha de la sesión
        return Carbon::parse($this->fecha_sesion)->addHours(48);
    }

    public function estaCerrada(): bool
    {
        // Devuelve TRUE si la fecha actual ya superó el límite de edición
        return now()->greaterThan($this->limiteEdicion());
    }

    public function horasRestantesEdicion(): int
    {
        $limite = $this->limiteEdicion();
        $ahora = now();
        
        // Si ya pasó el tiempo devuelve 0, si no, devuelve la diferencia en horas
        return $ahora->lessThan($limite) ? $ahora->diffInHours($limite) : 0;
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