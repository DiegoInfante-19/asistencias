<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaPnf extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'empresa_pnf';
    protected $primaryKey = 'id_empresa_pnf';

    // Desactivar timestamps por ser una tabla de configuración/relación (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_empresa',
        'id_pnf',
        'tipo_relacion',
        'observacion_empresa_pnf'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Una relación Empresa-PNF pertenece a una empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    // Una relación Empresa-PNF pertenece a un PNF
    public function pnf()
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }
}