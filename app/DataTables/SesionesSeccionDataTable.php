<?php

namespace App\DataTables;

use App\Models\Sesion;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Carbon\Carbon;

class SesionesSeccionDataTable extends BaseDataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('fecha_sesion', function ($sesion) {
                $carbonDate = Carbon::parse($sesion->fecha_sesion)->locale('es');
                $diaSemana = ucfirst($carbonDate->dayName);
                $diaMes = $carbonDate->format('d');
                $mes = $carbonDate->monthName;
                $anio = $carbonDate->format('Y');

                $formatoFecha = "{$diaSemana}, {$diaMes} de {$mes} del año {$anio}";

                return '<i class="bi bi-calendar-event me-1 text-secondary"></i> <span class="fw-semibold text-dark">' . $formatoFecha . '</span>';
            })
            ->addColumn('profesor_cargo', function ($sesion) {
                $nombre = $sesion->profesor->user->name_users ?? 'No asignado';
                $apellido = $sesion->profesor->user->last_name_users ?? '';
                return trim($nombre . ' ' . $apellido);
            })
            ->addColumn('estado_asistencia', function ($sesion) {
                // 1. Verificamos si ya guardó alumnos
                if ($sesion->asistencias_count == 0) {
                    return '<span class="badge bg-danger shadow-sm"><i class="bi bi-exclamation-octagon me-1"></i> Pendiente</span>';
                }

                // 2. Si ya pasó el tiempo (Modo Lectura)
                if ($sesion->estaCerrada()) {
                    return '<span class="badge bg-secondary shadow-sm"><i class="bi bi-lock-fill me-1"></i> Registrada (Cerrada)</span>';
                }

                // 3. Si guardó y todavía hay tiempo de editar
                $horas = $sesion->horasRestantesEdicion();
                return '
                    <span class="badge bg-success shadow-sm"><i class="bi bi-check-circle-fill me-1"></i> Registrada</span>
                    <div class="text-muted small fw-bold mt-1" style="font-size: 0.75rem;">
                        <i class="bi bi-clock-history text-warning"></i> Quedan '.$horas.'h para editar
                    </div>
                ';
            })
            ->addColumn('action', function ($sesion) {
                return view('secciones.partials.actions_sesiones', compact('sesion'))->render();
            })
            ->rawColumns(['fecha_sesion', 'estado_asistencia', 'action']) // Agregamos estado_asistencia al rawColumns
            ->setRowId('id_sesiones');
    }

    public function query(Sesion $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['profesor.user'])
            ->withCount('asistencias') // <--- CLAVE PARA NO SOBRECARGAR LA BASE DE DATOS
            ->where('id_seccion', $this->id_seccion);
    }

    protected function getTableId(): string
    {
        return 'sesiones-seccion-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->ajax([
                'url' => route('secciones.show', $this->id_seccion),
                'data' => 'function(d) { d.table = "sesiones-seccion-table"; }'
            ])
            ->orderBy(0, 'desc'); 
    }

    protected function getColumns(): array
    {
        return [
            Column::make('fecha_sesion')->title('Fecha'),
            Column::make('profesor_cargo')->title('Profesor a Cargo')->searchable(false)->orderable(false),
            Column::computed('estado_asistencia')->title('Estado')->exportable(false)->printable(false)->addClass('text-center align-middle'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center align-middle'),
        ];
    }
}