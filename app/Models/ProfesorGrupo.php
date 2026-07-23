<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfesorGrupo extends Model
{
    use HasFactory;

    protected $table = 'profesor_grupo';
    protected $primaryKey = 'id_profesor_grupo';

    protected $fillable = [
        'id_profesor',
        'id_grupo'
    ];

    /**
     * RELACIONES ELOQUENT
     */

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'id_profesor', 'id_profesor');
    }

    public function grupo()
    {
        return $this->belongsTo(GrupoAcademico::class, 'id_grupo', 'id_grupo');
    }
}