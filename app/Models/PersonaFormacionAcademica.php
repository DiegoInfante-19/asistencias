<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class PersonaFormacionAcademica extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria
    protected $table = 'persona_formacion_academica';
    protected $primaryKey = 'id_persona_formacion_academica';

    // Seguridad de Asignación Masiva ampliada con los nuevos campos de metadatos externos
    protected $fillable = [
        'id_personas',
        'id_titulos_pnf',
        'id_titulos',
        'origen_formacion',
        'mencion_especifica',
        'institucion_emisora',
        'anio_egreso',
        'observacion_formacion_academica'
    ];

    /**
     * RELACIONES
     */

    // La formación pertenece a una persona
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    // La formación pertenece a un Título-PNF específico (Modo Interno)
    public function tituloPnf(): BelongsTo
    {
        return $this->belongsTo(TituloPnf::class, 'id_titulos_pnf', 'id_titulos_pnf');
    }

    // La formación pertenece a un título base (Modo Externo o General)
    public function titulo(): BelongsTo
    {
        return $this->belongsTo(Titulo::class, 'id_titulos', 'id_titulos');
    }

    // Relación directa al PNF a través de TituloPnf usando hasOneThrough
    public function pnf(): HasOneThrough
    {
        return $this->hasOneThrough(
            Pnf::class,
            TituloPnf::class,
            'id_titulos_pnf', // Llave foránea en TituloPnf
            'id_pnf',         // Llave foránea en Pnf
            'id_titulos_pnf', // Llave local en PersonaFormacionAcademica
            'id_pnf'          // Llave local en TituloPnf
        );
    }

    /**
     * ACCESSORS & MUTATORS
     */

    // Accessor virtual inteligente para resolver el nombre comercial completo del título
    public function getNombreCompletoTituloAttribute(): string
    {
        if ($this->origen_formacion === 'Interno' && $this->tituloPnf) {
            return $this->tituloPnf->nombre_titulo_pnf;
        }

        $base = $this->titulo->nombre_titulo_base ?? 'Título General';
        
        if (!empty($this->mencion_especifica)) {
            return "{$base} - {$this->mencion_especifica}";
        }

        return $base;
    }
}