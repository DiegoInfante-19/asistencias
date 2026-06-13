<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acreditacion extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'acreditaciones';
    protected $primaryKey = 'id_acreditaciones';

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_personas',
        'id_empresa',
        'id_pnf',
        'estatus_acreditacion'
    ];

    /**
     * RELACIONES (Paso 6)
     * Estas relaciones permiten conectar los datos del ecosistema.
     */

    // Una acreditación pertenece a una persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    // Una acreditación pertenece a una empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    // Una acreditación pertenece a un PNF
    public function pnf()
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }
}