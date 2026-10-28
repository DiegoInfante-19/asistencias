<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitulacionPersona extends Model
{
    protected $table = 'titulacion_personas';
    protected $primaryKey = 'id_titulacion_personas';

    public $timestamps = false;

    protected $fillable = [
        'id_personas',
        'id_titulacion',
        'id_titulos_pnf',
        'id_pnf',
        'id_estatus_expediente'
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    public function titulacion(): BelongsTo
    {
        return $this->belongsTo(Titulo::class, 'id_titulacion', 'id_titulos');
    }

    public function tituloPnf(): BelongsTo
    {
        return $this->belongsTo(TituloPnf::class, 'id_titulos_pnf', 'id_titulos_pnf');
    }

    public function pnf(): BelongsTo
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }

    public function estatus(): BelongsTo
    {
        return $this->belongsTo(EstatusExpediente::class, 'id_estatus_expediente', 'id_estatus_expediente');
    }

    /**
     * ACCESSOR INTELIGENTE Y OPTIMIZADO
     * Resuelve el nombre comercial exacto priorizando la relación id_titulos_pnf
     */
    public function getNombreTituloEspecificoAttribute()
    {
        // 1. Prioridad: Si la relación ya viene cargada en memoria por Eager Loading
        if ($this->relationLoaded('tituloPnf') && $this->tituloPnf) {
            return $this->tituloPnf->nombre_titulo_pnf;
        }

        // 2. Prioridad: Búsqueda exacta por id_titulos_pnf en caché estática
        static $titulosPnfById = null;
        if ($titulosPnfById === null) {
            $titulosPnfById = \App\Models\TituloPnf::all()->keyBy('id_titulos_pnf');
        }

        if ($this->id_titulos_pnf && isset($titulosPnfById[$this->id_titulos_pnf])) {
            return $titulosPnfById[$this->id_titulos_pnf]->nombre_titulo_pnf;
        }

        // 3. Fallback histórico para registros antiguos sin id_titulos_pnf
        static $titulosPnfLegacy = null;
        if ($titulosPnfLegacy === null) {
            $titulosPnfLegacy = \App\Models\TituloPnf::all()->groupBy(function ($item) {
                return $item->id_pnf . '-' . $item->id_titulo;
            });
        }

        $key = $this->id_pnf . '-' . $this->id_titulacion;
        return isset($titulosPnfLegacy[$key]) ? $titulosPnfLegacy[$key]->first()->nombre_titulo_pnf : null;
    }
}