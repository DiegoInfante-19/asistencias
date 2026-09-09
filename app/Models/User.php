<?php

namespace App\Models;

use App\Models\PreguntaSecreta;
use App\Models\Profesor; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

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

    /*
    |--------------------------------------------------------------------------
    | CONSTANTES DE ROLES (RBAC)
    |--------------------------------------------------------------------------
    | Usar constantes evita los "números mágicos" y hace que verificar 
    | los roles sea instantáneo, sin hacer consultas extra a la Base de Datos.
    */
    public const ROLE_ADMINISTRADOR = 1;
    public const ROLE_COORDINADOR   = 2;
    public const ROLE_PROFESOR      = 3;


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

    public function profesor(): HasOne
    {
        return $this->hasOne(Profesor::class, 'id_users', 'id_users');
    }

    /**
     * ==========================================================
     * HELPERS DE IDENTIDAD Y SEGURIDAD (RBAC)
     * ==========================================================
     */

    public function isAdmin(): bool
    {
        return (int) $this->id_rol === self::ROLE_ADMINISTRADOR;
    }

    public function isCoordinador(): bool
    {
        return (int) $this->id_rol === self::ROLE_COORDINADOR;
    }

    public function isProfesor(): bool
    {
        // Refactorizado: Ahora verifica el ID directamente (Más rápido y seguro)
        return (int) $this->id_rol === self::ROLE_PROFESOR;
    }

    /**
     * ==========================================================
     * LOCAL SCOPES PARA FILTROS (DATATABLES)
     * ==========================================================
     */

    // Filtro 1: Tiene Sección (1) / No tiene Sección (0)
    public function scopeTieneSeccion(Builder $query, $booleano): Builder
    {
        if ($booleano === '1') {
            return $query->has('profesor.secciones');
        } elseif ($booleano === '0') {
            return $query->doesntHave('profesor.secciones');
        }
        return $query;
    }

    // Filtro 2: Tiene PNF (1) / No tiene PNF (0)
    public function scopeTienePnf(Builder $query, $booleano): Builder
    {
        if ($booleano === '1') {
            return $query->whereHas('profesor', function ($q) {
                $q->whereNotNull('id_pnf');
            });
        } elseif ($booleano === '0') {
            return $query->where(function ($q) {
                $q->doesntHave('profesor')
                  ->orWhereHas('profesor', function ($q2) {
                      $q2->whereNull('id_pnf');
                  });
            });
        }
        return $query;
    }

    // Filtro 3: Tiene Secciones Activas (1) / No tiene (0)
    public function scopeSeccionActiva(Builder $query, $booleano): Builder
    {
        if ($booleano === '1') {
            return $query->whereHas('profesor.secciones', function ($q) {
                $q->where('estatus_seccion', 'Activa');
            });
        } elseif ($booleano === '0') {
            return $query->whereDoesntHave('profesor.secciones', function ($q) {
                $q->where('estatus_seccion', 'Activa');
            });
        }
        return $query;
    }
}