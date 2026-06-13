<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pnf extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'pnfs';
    protected $primaryKey = 'id_pnf';

    // Desactivar timestamps por ser catálogo (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'nombre_pnf',
        'descripcion_pnf',
        'vigencia_pnf'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Un PNF puede tener muchas acreditaciones vinculadas
    public function acreditaciones(): HasMany
    {
        return $this->hasMany(Acreditacion::class, 'id_pnf', 'id_pnf');
    }

    // Un PNF puede tener muchas empresas asociadas
    public function empresas(): HasMany
    {
        return $this->hasMany(EmpresaPnf::class, 'id_pnf', 'id_pnf');
    }
}