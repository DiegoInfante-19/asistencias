<?php

namespace App\Policies;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AsistenciaPolicy
{
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
        $idProfesor = $user->profesor?->id_profesor;
        return $asistencia->sesion->profesorPerteneceASeccion($idProfesor);
    }

    public function create(User $user): bool
    {
        return $user->isProfesor();
    }

    public function update(User $user, Asistencia $asistencia): Response|bool
    {
        $idProfesor = $user->profesor?->id_profesor;

        if (!$asistencia->sesion->profesorPerteneceASeccion($idProfesor)) {
            return false;
        }

        // SE ELIMINÓ LA VALIDACIÓN DE LA VENTANA DE TIEMPO AQUÍ

        return true;
    }

    public function delete(User $user, Asistencia $asistencia): bool
    {
        $idProfesor = $user->profesor?->id_profesor;
        return $asistencia->sesion->profesorPerteneceASeccion($idProfesor);
    }
}