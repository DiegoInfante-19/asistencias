<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profesor extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'profesores';
    protected $primaryKey = 'id_profesor';

    // Desactivar timestamps por ser tabla de configuración (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_users',
        'id_pnf',
        'fecha_asignacion_profesor'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // El profesor pertenece a un usuario del sistema
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    // El profesor pertenece a un PNF específico
    public function pnf(): BelongsTo
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }

    // Un profesor puede dictar muchas sesiones
    public function sesiones(): HasMany
    {
        return $this->hasMany(Sesion::class, 'id_profesor', 'id_profesor');
    }
}