<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon; // <-- Importamos Carbon
use App\Models\PeriodoReceso; // <-- Importamos el modelo de Recesos

class PeriodoAcademico extends Model
{
    protected $table = 'periodos_academicos';
    protected $primaryKey = 'id_periodo';
    
    protected $fillable = [
        'id_cohortes', 
        'fecha_inicio', 
        'fecha_fin', 
        'estatus_periodo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function cohorte(): BelongsTo
    {
        return $this->belongsTo(Cohorte::class, 'id_cohortes', 'id_cohortes');
    }

    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class, 'id_periodo', 'id_periodo');
    }

    /**
     * Eventos del modelo PeriodoAcademico.
     */
    protected static function booted()
    {
        // 1. Cuando se CREA un nuevo periodo académico
        static::created(function ($periodo) {
            $periodo->sincronizarRecesosGenerales();
        });

        // 2. Cuando se ACTUALIZA un periodo académico existente
        static::updated(function ($periodo) {
            // Sincronización del estatus con las secciones
            if ($periodo->isDirty('estatus_periodo')) {
                $estatusSeccion = $periodo->estatus_periodo === 'Activo' ? 'Activa' : 'Inactiva';
                foreach ($periodo->secciones as $seccion) {
                    $seccion->update([
                        'estatus_seccion' => $estatusSeccion
                    ]);
                }
            }

            // Si se actualizaron explícitamente las FECHAS del periodo, volvemos a sincronizar los recesos
            if ($periodo->isDirty(['fecha_inicio', 'fecha_fin'])) {
                $periodo->sincronizarRecesosGenerales();
            }
        });
    }

    /**
     * LÓGICA DE NEGOCIO: Ajusta los años (y fechas lunares) de los Periodos de Receso
     */
    public function sincronizarRecesosGenerales()
    {
        $yearInicio = Carbon::parse($this->fecha_inicio)->year;
        $yearFin = Carbon::parse($this->fecha_fin)->year;

        // Obtenemos solo los Fijos (1) y Móviles (2). Los Únicos (3) se ignoran.
        $recesos = PeriodoReceso::whereIn('tipo_receso', ['1', '2'])->get();

        foreach ($recesos as $receso) {
            $inicioReceso = Carbon::parse($receso->fecha_inicio_periodo_receso);
            $finReceso = Carbon::parse($receso->fecha_fin_periodo_receso);
            
            // LÓGICA DE ASIGNACIÓN DE AÑO:
            // Agosto a Diciembre -> Año de Inicio | Enero a Julio -> Año de Fin
            $mes = $inicioReceso->month;
            $yearObjetivo = ($mes >= 8) ? $yearInicio : $yearFin;
            
            // === TIPO 2: RECESOS MÓVILES (Calculo Lunar) ===
            if ($receso->tipo_receso == '2') {
                $nombre = mb_strtolower($receso->nombre_periodo_receso);
                
                if (str_contains($nombre, 'semana santa')) {
                    // La Semana Santa es de Lunes a Viernes antes del Domingo de Resurrección
                    $easter = Carbon::createFromTimestamp(easter_date($yearObjetivo));
                    $receso->fecha_inicio_periodo_receso = $easter->copy()->subDays(6)->format('Y-m-d');
                    $receso->fecha_fin_periodo_receso = $easter->copy()->subDays(2)->format('Y-m-d');
                    $receso->save();
                    continue; // Pasamos al siguiente receso
                }
                
                if (str_contains($nombre, 'carnaval')) {
                    // La Semana de Carnaval es 48 días exactos antes del Domingo de Resurrección
                    $easter = Carbon::createFromTimestamp(easter_date($yearObjetivo));
                    $receso->fecha_inicio_periodo_receso = $easter->copy()->subDays(48)->format('Y-m-d'); // Lunes
                    $receso->fecha_fin_periodo_receso = $easter->copy()->subDays(44)->format('Y-m-d'); // Viernes
                    $receso->save();
                    continue; // Pasamos al siguiente receso
                }
            }

            // === TIPO 1: RECESOS FIJOS ===
            $inicioReceso->year = $yearObjetivo;
            $finReceso->year = $yearObjetivo;

            // Blindaje para Años Bisiestos: Si el receso caía 29 de feb y el nuevo año no es bisiesto
            if ($mes == 2 && $inicioReceso->day == 29 && !$inicioReceso->isLeapYear()) {
                $inicioReceso->day = 28;
            }
            if ($finReceso->month == 2 && $finReceso->day == 29 && !$finReceso->isLeapYear()) {
                $finReceso->day = 28;
            }

            $receso->fecha_inicio_periodo_receso = $inicioReceso->format('Y-m-d');
            $receso->fecha_fin_periodo_receso = $finReceso->format('Y-m-d');
            $receso->save();
        }
    }
}