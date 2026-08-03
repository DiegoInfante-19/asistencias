<?php

namespace App\Policies;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Carbon\Carbon;

class AsistenciaPolicy
{
    /**
     * ==========================================================
     * EL SUPERPODER (Filtro Global)
     * ==========================================================
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin() || $user->isCoordinador()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isProfesor();
    }

    public function view(User $user, Asistencia $asistencia): bool
    {
        return $user->profesor?->id_profesor === $asistencia->sesion->id_profesor;
    }

    public function create(User $user): bool
    {
        return $user->isProfesor();
    }

    public function update(User $user, Asistencia $asistencia): Response|bool
    {
        if ($user->profesor?->id_profesor !== $asistencia->sesion->id_profesor) {
            return false;
        }

        // REGLA DE NEGOCIO: Ventana de tiempo de 48 horas
        $horasPermitidas = 48;
        $fechaLimite = Carbon::parse($asistencia->sesion->fecha_sesion)->addHours($horasPermitidas);

        if (Carbon::now()->greaterThan($fechaLimite)) {
            return Response::deny("El tiempo límite de {$horasPermitidas} horas para modificar esta asistencia ha expirado.");
        }

        return true;
    }

    public function delete(User $user, Asistencia $asistencia): bool
    {
        return $user->profesor?->id_profesor === $asistencia->sesion->id_profesor;
    }
}