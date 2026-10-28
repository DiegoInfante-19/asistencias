<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TituloPnf extends Model
{
    use SoftDeletes;

    protected $table = 'titulos_pnf'; 
    protected $primaryKey = 'id_titulos_pnf';
    
    protected $fillable = [
        'id_pnf',
        'id_titulo',
        'nombre_titulo_pnf'
    ];

    /**
     * RELACIONES
     */

    // Pertenece a un Programa Nacional de Formación
    public function pnf(): BelongsTo
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }

    // Pertenece a un Título base
    public function titulo(): BelongsTo
    {
        return $this->belongsTo(Titulo::class, 'id_titulo', 'id_titulos');
    }

    // Formaciones académicas previas vinculadas
    public function formacionesAcademicas(): HasMany
    {
        return $this->hasMany(PersonaFormacionAcademica::class, 'id_titulos_pnf', 'id_titulos_pnf');
    }

    // Expedientes académicos/titulaciones activas de personas asociadas a este título
    public function titulacionesPersonas(): HasMany
    {
        return $this->hasMany(TitulacionPersona::class, 'id_titulos_pnf', 'id_titulos_pnf');
    }
}