<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    //1. Campos que se pueden llenar
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /*
    Relacion: Un Rol lo tienen muchos Usuarios
    Esto te permite hacer: $rol->users 
    */
    public function users(): HasMany{
        return $this->hasMany(User::class);
    }
}
