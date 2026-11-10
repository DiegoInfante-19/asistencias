<?php

namespace App\Traits;

trait CalculaMetricasAsistencia
{
    /**
     * Obtiene el total de clases que realmente han sido dictadas y evaluadas.
     */
    public function getTotalClasesEvaluadasAttribute()
    {
        // 1. Rendimiento extremo: Si Yajra Datatables inyectó los totales vía subconsulta SQL, los usamos.
        if (isset($this->attributes['total_presentes']) && isset($this->attributes['total_ausentes'])) {
            return $this->attributes['total_presentes'] + $this->attributes['total_ausentes'];
        }

        // 2. Cálculo en memoria (Ideal para el Service de PNF donde cargamos con "With")
        if ($this->relationLoaded('asistencias')) {
            return $this->asistencias->whereIn('estado_asistencia', ['presente', 'ausente', 'tarde', 'justificada'])->count();
        }

        // 3. Fallback directo a Base de Datos
        return $this->asistencias()->whereIn('estado_asistencia', ['presente', 'ausente', 'tarde', 'justificada'])->count();
    }

    /**
     * Obtiene el total de faltas.
     */
    public function getTotalInasistenciasAttribute()
    {
        // Si viene del SQL de Datatables
        if (isset($this->attributes['total_ausentes'])) {
            return $this->attributes['total_ausentes'];
        }

        if ($this->relationLoaded('asistencias')) {
            return $this->asistencias->where('estado_asistencia', 'ausente')->count();
        }

        return $this->asistencias()->where('estado_asistencia', 'ausente')->count();
    }

    /**
     * Retorna el arreglo estandarizado con los colores, texto y porcentaje.
     */
    public function getMetricasSemaforoAttribute()
    {
        $totalClases = $this->total_clases_evaluadas;
        $faltas = $this->total_inasistencias;

        if ($totalClases === 0) {
            return [
                'texto'      => 'SIN DATOS',
                'clase_css'  => 'bg-secondary',
                'porcentaje' => 0
            ];
        }

        // Usamos el porcentaje exacto de SQL si existe, sino lo calculamos aquí
        $porcentaje = isset($this->attributes['porcentaje_inasistencia']) 
            ? (float) $this->attributes['porcentaje_inasistencia'] 
            : round(($faltas / $totalClases) * 100, 2);

        if ($porcentaje >= 25) {
            return [
                'texto'      => 'REPROBADO',
                'clase_css'  => 'bg-danger',
                'porcentaje' => $porcentaje
            ];
        } elseif ($porcentaje >= 15) {
            return [
                'texto'      => 'ALERTA',
                'clase_css'  => 'bg-warning text-dark',
                'porcentaje' => $porcentaje
            ];
        }

        return [
            'texto'      => 'SEGURO',
            'clase_css'  => 'bg-success',
            'porcentaje' => $porcentaje
        ];
    }
}