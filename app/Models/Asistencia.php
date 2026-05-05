<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Asistencia
 *
 * @property $id
 * @property $fecha
 * @property $miembro_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Miembro $miembro
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Asistencia extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['fecha', 'miembro_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function miembro()
    {
        return $this->belongsTo(\App\Models\Miembro::class, 'miembro_id', 'id');
    }
    
}
