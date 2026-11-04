<?php

namespace App\Policies;

use App\Models\Sesion;
use App\Models\Seccion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SesionPolicy
{
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

        return null; 
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Sesion $sesion): bool
    {
        if ($user->isProfesor()) {
            $idProfesor = $user->profesor?->id_profesor;
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
        if ($sesion->relationLoaded('seccion') ? $sesion->seccion && !$sesion->seccion->estaActiva() : !$sesion->seccion->estaActiva()) {
            return Response::deny('Esta sesión pertenece a una sección inactiva y su histórico se encuentra bloqueado.');
        }

        if ($user->isProfesor()) {
            $idProfesor = $user->profesor?->id_profesor;

            if (!$sesion->profesorPerteneceASeccion($idProfesor)) {
                return Response::deny('No tiene autorización para modificar sesiones de esta sección.');
            }
        }

        return true;
    }

    public function delete(User $user, Sesion $sesion): Response|bool
    {
        if ($user->isProfesor()) {
            return Response::deny('Los profesores no pueden anular sesiones. Contacte al encargado.');
        }

        return true;
    }
}