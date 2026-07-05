<?php

namespace App\Models;
use App\Models\PreguntaSecreta;
use App\Models\Profesor; // IMPORTANTE: Asegúrate de importar el modelo Profesor
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
     * EVENTOS DEL MODELO
     * ==========================================================
     */
    protected static function booted()
    {
        static::created(function ($user) {
            PreguntaSecreta::create([
                'id_users'   => $user->id_users,
                'pregunta1'  => '',
                'pregunta2'  => '',
                'respuesta1' => '',
                'respuesta2' => '',
            ]);
        });
    }

    public function getAuthPassword()
    {
        return $this->password_users;
    }

    /**
     * ==========================================================
     * RELACIONES
     * ==========================================================
     */

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
    }

    public function preguntasSecretas(): HasOne
    {
        return $this->hasOne(PreguntaSecreta::class, 'id_users', 'id_users');
    }

    // NUEVO: Relación 1 a 1 con el perfil de Profesor
    public function profesor(): HasOne
    {
        return $this->hasOne(Profesor::class, 'id_users', 'id_users');
    }

    /**
     * ==========================================================
     * HELPERS (Funciones auxiliares)
     * ==========================================================
     */

    // NUEVO: Verifica si el usuario activo tiene el rol de Profesor
    public function isProfesor(): bool
    {
        // Importante: Verifica que el nombre exacto de tu rol de profesor en la BD sea 'Profesor'.
        // Si lo guardaste como 'Docente' u otra cosa, debes cambiar la palabra 'Profesor' de abajo.
        return $this->rol && strtolower($this->rol->nombre_rol) === 'profesor';
    }
}