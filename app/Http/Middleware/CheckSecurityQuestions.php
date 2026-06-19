<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CheckSecurityQuestions
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $preguntas = $user->preguntasSecretas;

            // Verificamos si no existe el registro o si la pregunta1 está vacía (como lo configuramos en el modelo)
            if (!$preguntas || empty($preguntas->pregunta1)) {
                // Compartimos esta variable con todas las vistas Blade
                View::share('requireSecuritySetup', true);
            } else {
                View::share('requireSecuritySetup', false);
            }
        }

        return $next($request);
    }
}