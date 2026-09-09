<?php

namespace App\Policies;

use App\Models\Sesion;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Carbon\Carbon;

class SesionPolicy
{
    /**
     * El Encargado / Administrador / Coordinador tiene superpoderes globales.
     */
    public function before(User $user, string $ability): ?bool
    {
        // Asumiendo que tus métodos de rol son isAdministrador() o isCoordinador()
        if (method_exists($user, 'isAdministrador') && $user->isAdministrador()) {
            return true;
        }
        if (method_exists($user, 'isCoordinador') && $user->isCoordinador()) {
            return true;
        }
        // Compatibilidad con métodos alternativos por si usas isAdmin()
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
            return $user->profesor?->id_profesor === $sesion->id_profesor;
        }
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isProfesor() || $user->isAdministrador() || $user->isCoordinador();
    }

    public function update(User $user, Sesion $sesion): Response|bool
    {
        // Si es profesor, validamos propiedad y ventana de gracia de 48 horas
        if ($user->isProfesor()) {
            if ($user->profesor?->id_profesor !== $sesion->id_profesor) {
                return Response::deny('No tiene autorización para modificar sesiones de otros profesores.');
            }

            $horasPermitidas = 48;
            $fechaLimite = Carbon::parse($sesion->fecha_sesion)->addHours($horasPermitidas);

            if (Carbon::now()->greaterThan($fechaLimite)) {
                return Response::deny("El tiempo límite de {$horasPermitidas} horas para modificar esta asistencia ha expirado. Contacte a su Coordinador.");
            }
        }

        return true;
    }

    public function delete(User $user, Sesion $sesion): Response|bool
    {
        // La anulación (Soft Delete) queda reservada exclusivamente para el Encargado por seguridad institucional
        if ($user->isProfesor()) {
            return Response::deny('Los profesores no pueden anular sesiones. Contacte al encargado.');
        }

        return true;
    }
}