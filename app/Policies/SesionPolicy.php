<?php

namespace App\Policies;

use App\Models\Sesion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SesionPolicy
{
    /**
     * El Encargado / Administrador / Coordinador tiene superpoderes globales.
     * Si retorna true, salta las demás validaciones.
     */
    public function before(User $user, string $ability): ?bool
    {
        if (method_exists($user, 'isAdministrador') && $user->isAdministrador()) {
            return true;
        }
        if (method_exists($user, 'isCoordinador') && $user->isCoordinador()) {
            return true;
        }
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        return null; // Si es profesor, continúa a evaluar los métodos de abajo
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

   public function view(User $user, Sesion $sesion): bool
    {
        if ($user->isProfesor()) {
            
            // --- DEPURADOR TEMPORAL ---
            dd([
                'id_user_logueado' => $user->id,
                'email' => $user->email,
                'es_profesor_según_isProfesor' => $user->isProfesor(),
                'relacion_profesor_objeto' => $user->profesor,
                'id_profesor_del_usuario' => $user->profesor?->id_profesor,
                'id_profesor_de_la_sesion' => $sesion->id_profesor,
            ]);
            // ---------------------------

            return (int) $user->profesor?->id_profesor === (int) $sesion->id_profesor;
        }
        return true;
    }

    public function create(User $user): bool
    {
        return true; 
    }

    public function update(User $user, Sesion $sesion): Response|bool
    {
        // Si el flujo llegó aquí, significa que ES UN PROFESOR
        if ($user->isProfesor()) {
            
            // 1. Validar Propiedad con casteo seguro
            if ((int) $user->profesor?->id_profesor !== (int) $sesion->id_profesor) {
                return Response::deny('No tiene autorización para modificar sesiones de otros profesores.');
            }

            // 2. Validar Tiempo: ¿Pasaron las 48 horas?
            if ($sesion->tiempoAgotado()) {
                return Response::deny("El tiempo límite de 48 horas para modificar esta asistencia ha expirado. Contacte a su Coordinador.");
            }
        }

        return true;
    }

    public function delete(User $user, Sesion $sesion): Response|bool
    {
        // La anulación (Soft Delete) queda reservada exclusivamente para el Encargado
        if ($user->isProfesor()) {
            return Response::deny('Los profesores no pueden anular sesiones. Contacte al encargado.');
        }

        return true;
    }
}