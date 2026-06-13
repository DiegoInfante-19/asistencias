<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaPersona extends Model
{
    use SoftDeletes;

    // Configuración de tabla y llave primaria (Paso 5)
    protected $table = 'empresa_personas';
    protected $primaryKey = 'id_empresa_personas';

    // Seguridad de Asignación Masiva (Paso 5)
    protected $fillable = [
        'id_personas', 
        'id_empresa', 
        'id_cargo'
    ];

    /**
     * RELACIONES (Paso 6)
     * Conectamos este registro intermedio con sus padres.
     */

    // La persona dueña de este registro laboral
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_personas', 'id_personas');
    }

    // La empresa donde trabaja o trabajó
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    // El cargo que desempeña
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_cargo', 'id_cargo');
    }
}