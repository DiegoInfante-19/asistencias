<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';
    protected $primaryKey = 'id_cargo';

    // Eliminamos la línea public $timestamps = false; 
    // porque tu tabla SI tiene created_at y updated_at.
    
    protected $fillable = [
        'descripcion_cargo'
    ];
}