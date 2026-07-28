<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\NivelAcademico;

class Profesor extends Model
{
    protected $table = 'profesores';
    protected $primaryKey = 'id_profesor';

    public $timestamps = false;
    
    protected $fillable = [
        'id_users',
        'id_pnf',
        'nivel_asignado',
        'fecha_asignacion_profesor'
    ];

    protected $casts = [
        'nivel_asignado' => NivelAcademico::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function pnf(): BelongsTo
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }

    // Grupos académicos asignados a este profesor
    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(GrupoAcademico::class, 'profesor_grupo', 'id_profesor', 'id_grupo')
                    ->withTimestamps();
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(Sesion::class, 'id_profesor', 'id_profesor');
    }
}