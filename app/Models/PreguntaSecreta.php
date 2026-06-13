<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreguntaSecreta extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'preguntas_secretas';
    protected $primaryKey = 'id_preguntas_secretas';

    // Desactivar timestamps por ser un modelo de seguridad (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_users',
        'pregunta1',
        'pregunta2',
        'respuesta1',
        'respuesta2'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Cada registro de seguridad pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}