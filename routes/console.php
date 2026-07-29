<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your closure based console
| commands. Each closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Proyección automática anual de recesos y feriados
Schedule::command('recesos:proyectar')
    ->yearlyOn(12, 1, '00:00') // Se ejecuta automáticamente cada 1 de diciembre a las 00:00 hrs
    ->onSuccess(function () {
        Log::info('El comando de proyección de recesos se ejecutó exitosamente.');
    })
    ->onFailure(function () {
        Log::error('Fallo crítico al intentar ejecutar la proyección automática de recesos.');
    });


    /*
|--------------------------------------------------------------------------
| 📌 GUÍA DE DESPLIEGUE EN PRODUCCIÓN (SERVIDOR LINUX / CRON JOB)
|--------------------------------------------------------------------------
| Cuando subas este sistema siscontrolasistencias a un servidor de producción, 
| debes activar el "Reloj" del sistema operativo para que el Schedule de arriba 
| funcione de forma autónoma. Sigue estos pasos en la terminal del servidor:
|
| PASO 1: Accede a la terminal de tu servidor y edita el crontab del usuario:
|         crontab -e
|
| PASO 2: Agrega la siguiente línea al final del archivo (reemplaza la ruta 
|         por la ruta absoluta real de tu proyecto en el disco):
|         * * * * * cd /ruta/completa/a/tu/proyecto && php artisan schedule:run >> /dev/null 2>&1
|
| PASO 3: Guarda y cierra el archivo. (Esto le indica a Linux que despierte 
|         a Laravel cada minuto para que evalúe si debe ejecutar la tarea).
|
| PRUEBA MANUAL EN PRODUCCIÓN (Opcional):
| Puedes verificar que el comando responda bien en el servidor escribiendo:
| php artisan recesos:proyectar
|--------------------------------------------------------------------------
*/