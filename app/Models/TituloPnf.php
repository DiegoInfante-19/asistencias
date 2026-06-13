<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TituloPnf extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'titulos_pnf'; 
    protected $primaryKey = 'id_titulos_pnf';
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_pnf',
        'id_titulo',
        'nombre_titulo_pnf'
    ];

    /**
     * RELACIONES (Paso 6)
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
}