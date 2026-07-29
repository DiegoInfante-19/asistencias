<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeriodoReceso;
use App\Services\RecesoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProyectarRecesosAnuales extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     * Ejemplo de uso: php artisan recesos:proyectar
     */
    protected $signature = 'recesos:proyectar {--anio= : Año específico a proyectar (Opcional)}';

    /**
     * La descripción del comando.
     */
    protected $description = 'Proyecta y genera automáticamente los períodos de receso y feriados para el año siguiente.';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle(RecesoService $recesoService)
    {
        // 1. Definir el año de origen (actual) y el año destino (siguiente)
        $anioActual = Carbon::now()->year;
        
        // Si el usuario pasó un parámetro opcional --anio=2027, lo usamos; si no, calculamos el siguiente
        $anioDestino = $this->option('anio') ? (int) $this->option('anio') : $anioActual + 1;

        $this->info("Iniciando proyección de recesos desde el año {$anioActual} hacia el año {$anioDestino}...");

        // 2. Buscar todos los recesos del año actual
        $recesosActuales = PeriodoReceso::whereYear('fecha_inicio_periodo_receso', $anioActual)->get();

        if ($recesosActuales->isEmpty()) {
            $this->warn("No se encontraron períodos de receso registrados para el año {$anioActual}.");
            return 0;
        }

        $contadorCreados = 0;
        $contadorOmitidos = 0;

        foreach ($recesosActuales as $receso) {
            // 3. Llamar al Servicio (Fase 2) para calcular las nuevas fechas
            $nuevasFechas = $recesoService->calcularProyeccion($receso, $anioDestino);

            // Si retorna null (ej: porque el tipo es '3' - Único), saltamos este registro
            if (!$nuevasFechas) {
                $contadorOmitidos++;
                continue;
            }

            // Construir el nuevo nombre para el periodo (Ej: "Vacaciones 2026" -> "Vacaciones 2027")
            $nuevoNombre = str_replace($anioActual, $anioDestino, $receso->nombre_periodo_receso);
            // Si el nombre original no tenía el año explícito, se lo agregamos de manera limpia
            if (!str_contains($nuevoNombre, (string)$anioDestino)) {
                $nuevoNombre .= " " . $anioDestino;
            }

            // 4. Validación de seguridad: Verificar si ya existe un receso con ese nombre exacto en el año destino
            $existe = PeriodoReceso::where('nombre_periodo_receso', $nuevoNombre)->exists();

            if ($existe) {
                $this->line(" <comment>[Omitido]</comment> El receso '{$nuevoNombre}' ya existe.");
                continue;
            }

            // 5. Crear el nuevo registro en la base de datos
            PeriodoReceso::create([
                'nombre_periodo_receso'       => $nuevoNombre,
                'fecha_inicio_periodo_receso' => $nuevasFechas['inicio']->toDateString(),
                'fecha_fin_periodo_receso'    => $nuevasFechas['fin']->toDateString(),
                'descripcion_periodo_receso'  => $receso->descripcion_periodo_receso,
                'nivel_periodo_receso'        => $receso->nivel_periodo_receso,
                'suspension_actividades'      => $receso->suspension_actividades,
                'tipo_receso'                 => $receso->tipo_receso,
            ]);

            // Auditoría por Logs
            Log::info("Receso proyectado automáticamente: '{$nuevoNombre}' para el año {$anioDestino}.");
            $this->line(" <info>[Creado]</info> {$nuevoNombre} ({$nuevasFechas['inicio']->toDateString()} al {$nuevasFechas['fin']->toDateString()})");
            
            $contadorCreados++;
        }

        $this->info("¡Proyección finalizada con éxito! Se crearon {$contadorCreados} nuevos recesos. Omitidos/Únicos: {$contadorOmitidos}.");
        return 0;
    }
}