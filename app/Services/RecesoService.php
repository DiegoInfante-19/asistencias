<?php

namespace App\Services;

use App\Models\PeriodoReceso;
use Carbon\Carbon;

class RecesoService
{
    /**
     * Proyecta un periodo de receso al año indicado.
     * Retorna un arreglo con las nuevas fechas, o null si no se debe proyectar.
     */
    public function calcularProyeccion(PeriodoReceso $receso, int $anioDestino): ?array
    {
        // 1. Si es Único (Valor '3'), muere en su año original, no se proyecta.
        if ((string) $receso->tipo_receso === '3') {
            return null; 
        }

        $fechaInicioOrig = Carbon::parse($receso->fecha_inicio_periodo_receso);
        $fechaFinOrig = Carbon::parse($receso->fecha_fin_periodo_receso);

        // 2. Si es Fijo (Valor '1' ej: Navidad), solo reemplazamos el año.
        // Carbon se encarga automáticamente de los años bisiestos (ej. 29 feb -> 28 feb)
        if ((string) $receso->tipo_receso === '1') {
            return [
                'inicio' => $fechaInicioOrig->copy()->year($anioDestino),
                'fin'    => $fechaFinOrig->copy()->year($anioDestino),
            ];
        }

        // 3. Si es Móvil (Valor '2' ej: Semana Santa), usamos el algoritmo de distancia relativa a la Pascua
        if ((string) $receso->tipo_receso === '2') {
            $anioOrigen = $fechaInicioOrig->year;
            
            $pascuaOrigen = $this->obtenerPascua($anioOrigen);
            $pascuaDestino = $this->obtenerPascua($anioDestino);

            // Calculamos la distancia en días (puede dar negativo si es antes de Pascua, como Carnaval)
            $distanciaInicio = $pascuaOrigen->diffInDays($fechaInicioOrig, false);
            $distanciaFin    = $pascuaOrigen->diffInDays($fechaFinOrig, false);

            return [
                'inicio' => $pascuaDestino->copy()->addDays($distanciaInicio),
                'fin'    => $pascuaDestino->copy()->addDays($distanciaFin),
            ];
        }

        return null;
    }

    /**
     * Obtiene la fecha exacta del Domingo de Pascua para un año dado.
     * Utiliza la función matemática nativa easter_days() de PHP combinada con Carbon.
     */
    private function obtenerPascua(int $anio): Carbon
    {
        // easter_days() devuelve cuántos días después del 21 de marzo cae la Pascua ese año
        $diasDespues21Marzo = easter_days($anio);
        
        return Carbon::create($anio, 3, 21)->addDays($diasDespues21Marzo);
    }
}