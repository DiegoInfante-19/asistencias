<?php

namespace App\Policies;

use App\Models\Sesion;
use App\Models\Seccion;
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
            $idProfesor = $user->profesor?->id_profesor;
            // Autoriza si el profesor pertenece a la sección de esta sesión
            return $sesion->profesorPerteneceASeccion($idProfesor);
        }
        return true;
    }

    public function create(User $user, ?Seccion $seccion = null): Response|bool
    {
        if ($seccion && !$seccion->estaActiva()) {
            return Response::deny('La sección se encuentra inactiva; no se permiten nuevas sesiones.');
        }
        return true;
    }

    public function update(User $user, Sesion $sesion): Response|bool
    {
        // BLINDAJE: Si la sección padre no está activa, se bloquea por completo la edición
        if ($sesion->relationLoaded('seccion') ? $sesion->seccion && !$sesion->seccion->estaActiva() : !$sesion->seccion->estaActiva()) {
            return Response::deny('Esta sesión pertenece a una sección inactiva y su histórico se encuentra bloqueado.');
        }

        if ($user->isProfesor()) {
            $idProfesor = $user->profesor?->id_profesor;

            // Autoriza la edición si el profesor pertenece a la sección
            if (!$sesion->profesorPerteneceASeccion($idProfesor)) {
                return Response::deny('No tiene autorización para modificar sesiones de esta sección.');
            }

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