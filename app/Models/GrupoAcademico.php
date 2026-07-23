<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\NivelAcademico;

class GrupoAcademico extends Model
{
    use HasFactory;

    protected $table = 'grupos_academicos';
    protected $primaryKey = 'id_grupo';

    protected $fillable = [
        'id_cohortes',
        'id_pnf',
        'nivel_academico',
        'estatus_grupo'
    ];

    // Casteo automático del campo 'nivel_academico' al Enum de PHP
    protected $casts = [
        'nivel_academico' => NivelAcademico::class,
    ];

    /**
     * RELACIONES ELOQUENT
     */

    // Un grupo pertenece a una Cohorte global
    public function cohorte()
    {
        return $this->belongsTo(Cohorte::class, 'id_cohortes', 'id_cohortes');
    }

    // Un grupo pertenece a un PNF
    public function pnf()
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }

    // Profesores autorizados para evaluar este grupo (Muchos a Muchos vía pivote)
    public function profesores()
    {
        return $this->belongsToMany(Profesor::class, 'profesor_grupo', 'id_grupo', 'id_profesor')
                    ->withTimestamps();
    }

    // Inscripciones registradas en este grupo específico
    public function inscripciones()
    {
        return $this->hasMany(InscripcionCohorte::class, 'id_grupo', 'id_grupo');
    }

    // Sesiones de clase impartidas a este grupo
    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'id_grupo', 'id_grupo');
    }
}