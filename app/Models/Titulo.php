<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Titulo extends Model
{
    // Configuración de tabla y llave primaria
    protected $table = 'titulos';
    protected $primaryKey = 'id_titulos';

    // Desactivar timestamps por ser catálogo
    public $timestamps = false;
    
    // Seguridad de Asignación Masiva
    protected $fillable = [
        'nombre_titulo_base',
        'nivel_academico'
    ];

    /**
     * RELACIONES
     */

    // Un título base puede estar presente en muchas titulaciones de personas
    public function titulaciones(): HasMany
    {
        return $this->hasMany(TitulacionPersona::class, 'id_titulacion', 'id_titulos');
    }

    // Relación inversa: Un título base puede estar asociado a muchas formaciones académicas previas
    public function formacionesAcademicas(): HasMany
    {
        return $this->hasMany(PersonaFormacionAcademica::class, 'id_titulos', 'id_titulos');
    }
}