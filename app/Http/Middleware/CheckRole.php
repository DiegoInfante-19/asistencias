<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed  ...$roles  Roles permitidos pasados desde la ruta (ej: role:Administrador,Coordinador)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verificamos que el usuario esté autenticado (por seguridad por defecto)
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 2. Mapeo de nombres textuales de la ruta a nuestras constantes numéricas seguras
        $roleMap = [
            'Administrador' => User::ROLE_ADMINISTRADOR,
            'Coordinador'   => User::ROLE_COORDINADOR,
            'Profesor'      => User::ROLE_PROFESOR,
        ];

        $allowedRoleIds = [];
        foreach ($roles as $roleName) {
            if (isset($roleMap[$roleName])) {
                $allowedRoleIds[] = $roleMap[$roleName];
            }
        }

        // 3. Validar si el id_rol del usuario activo se encuentra dentro de los permitidos
        if (in_array((int) $user->id_rol, $allowedRoleIds, true)) {
            return $next($request);
        }

        // 4. Si no tiene privilegios, abortamos la petición con un Error 403 (Forbidden)
        abort(403, 'No tiene autorización para acceder a este módulo del sistema.');
    }
}