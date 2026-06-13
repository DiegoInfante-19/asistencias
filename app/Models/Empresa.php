<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';

    // Desactivar timestamps por ser catálogo (Paso 5)
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'nombre_empresa'
    ];

    /**
     * RELACIONES (Paso 6)
     */

    // Una empresa puede estar vinculada a muchas personas (historial laboral)
    public function empleados()
    {
        return $this->hasMany(EmpresaPersona::class, 'id_empresa', 'id_empresa');
    }

    // Una empresa puede tener muchas acreditaciones gestionadas
    public function acreditaciones()
    {
        return $this->hasMany(Acreditacion::class, 'id_empresa', 'id_empresa');
    }
}