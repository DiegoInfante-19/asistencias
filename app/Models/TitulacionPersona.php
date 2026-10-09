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

    public function pnf(): BelongsTo
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }

    public function estatus(): BelongsTo
    {
        return $this->belongsTo(EstatusExpediente::class, 'id_estatus_expediente', 'id_estatus_expediente');
    }

    /**
     * ACCESSOR INTELIGENTE Y OPTIMIZADO (Reemplaza a la vieja relación tituloPnf)
     * Cruza matemáticamente el PNF y el Título para dar el nombre exacto (Ej: "Ingeniero en Mecánica")
     */
    public function getNombreTituloEspecificoAttribute()
    {
        // Caché estática en memoria para evitar el problema N+1 en DataTables.
        // Carga el catálogo 1 sola vez sin importar si tienes 10 o 1000 estudiantes en la tabla.
        static $titulosPnf = null;
        
        if ($titulosPnf === null) {
            $titulosPnf = \App\Models\TituloPnf::all()->groupBy(function($item) {
                return $item->id_pnf . '-' . $item->id_titulo;
            });
        }
        
        // Creamos la llave combinando el PNF y el Título del estudiante
        $key = $this->id_pnf . '-' . $this->id_titulacion;
        
        // Retornamos el nombre específico si existe
        return isset($titulosPnf[$key]) ? $titulosPnf[$key]->first()->nombre_titulo_pnf : null;
    }
}