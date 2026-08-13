<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seccion extends Model
{
    protected $table = 'secciones';
    protected $primaryKey = 'id_seccion';
    
    protected $fillable = [
        'id_periodo', 
        'id_pnf', 
        'nombre_seccion', 
        'estatus_seccion'
    ];

    public function periodoAcademico(): BelongsTo
    {
        return $this->belongsTo(PeriodoAcademico::class, 'id_periodo', 'id_periodo');
    }

    public function pnf(): BelongsTo
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }

    // RELACIÓN N:M CON PROFESORES (La magia de la nueva arquitectura)
    public function profesores(): BelongsToMany
    {
        return $this->belongsToMany(Profesor::class, 'profesor_seccion', 'id_seccion', 'id_profesor')
                    ->withTimestamps();
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionSeccion::class, 'id_seccion', 'id_seccion');
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(Sesion::class, 'id_seccion', 'id_seccion');
    }
}