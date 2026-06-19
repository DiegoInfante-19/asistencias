<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreguntaSecreta extends Model
{
    protected $table = 'preguntas_secretas';
    protected $primaryKey = 'id_preguntas_secretas';

    public $timestamps = false;
    
    protected $fillable = [
        'id_users',
        'pregunta1',
        'pregunta2',
        'respuesta1',
        'respuesta2'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}