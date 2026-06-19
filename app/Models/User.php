<?php

namespace App\Models;
use App\Models\PreguntaSecreta;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_users';

    protected $fillable = [
        'name_users',
        'last_name_users',
        'cedula_users',
        'email_users',
        'phone_users',
        'username',
        'status_users',
        'id_rol',
        'last_login_at',
        'password_users',
    ];

    protected $hidden = [
        'password_users',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password_users' => 'hashed',
        ];
    }


    /**
     * ==========================================================
     * EVENTOS DEL MODELO (El disparador automático)
     * ==========================================================
     */
    protected static function booted()
    {
        // El evento 'created' se dispara DESPUÉS de que el usuario se guarda en la BD
        static::created(function ($user) {
            PreguntaSecreta::create([
                'id_users'   => $user->id_users, // Tomamos el ID recién generado
                'pregunta1'  => '',              // Cadenas vacías para superar el NOT NULL
                'pregunta2'  => '',
                'respuesta1' => '',
                'respuesta2' => '',
            ]);
        });
    }

    /**
     * IMPORTANTE: Sobrescribir el método de autenticación
     * para que Laravel use tu columna personalizada.
     */
    public function getAuthPassword()
    {
        return $this->password_users;
    }

    /**
     * RELACIONES (Paso 6)
     */

    // Un usuario pertenece a un rol
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
    }

    // Un usuario puede tener una configuración de preguntas secretas
    public function preguntasSecretas(): HasOne
    {
        return $this->hasOne(PreguntaSecreta::class, 'id_users', 'id_users');
    }
}