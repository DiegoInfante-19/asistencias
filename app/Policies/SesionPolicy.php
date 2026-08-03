<?php

namespace App\Policies;

use App\Models\Sesion;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Carbon\Carbon; // <--- Importación de Carbon para manejo de fechas

class SesionPolicy
{
    /**
     * ==========================================================
     * EL SUPERPODER (Filtro Global)
     * ==========================================================
     */
    public function before(User $user, string $ability): ?bool
    {
        // Los superiores ignoran todas las reglas (incluyendo el límite de 48h)
        if ($user->isAdmin() || $user->isCoordinador()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isProfesor();
    }

    public function view(User $user, Sesion $sesion): bool
    {
        return $user->profesor?->id_profesor === $sesion->id_profesor;
    }

    public function create(User $user): bool
    {
        return $user->isProfesor();
    }

    public function update(User $user, Sesion $sesion): Response|bool
    {
        // 1. Verificamos que sea el dueño de la clase
        if ($user->profesor?->id_profesor !== $sesion->id_profesor) {
            return false;
        }

        // 2. REGLA DE NEGOCIO: Ventana de tiempo de 48 horas
        $horasPermitidas = 48;
        $fechaLimite = Carbon::parse($sesion->fecha_sesion)->addHours($horasPermitidas);

        if (Carbon::now()->greaterThan($fechaLimite)) {
            return Response::deny("El tiempo límite de {$horasPermitidas} horas para modificar esta asistencia ha expirado. Contacte a su Coordinador.");
        }

        return true;
    }

    public function delete(User $user, Sesion $sesion): bool
    {
        return $user->profesor?->id_profesor === $sesion->id_profesor;
    }
}