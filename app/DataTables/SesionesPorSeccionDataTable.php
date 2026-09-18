<?php

namespace App\DataTables;

use App\Models\Sesion;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Carbon\Carbon;

class SesionesPorSeccionDataTable extends BaseDataTable
{
    protected $idSeccion;

    public function withIdSeccion(int $idSeccion): self
    {
        $this->idSeccion = $idSeccion;
        return $this;
    }

    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->editColumn('fecha_sesion', function ($sesion) {
                // Configuramos Carbon en español para obtener el día y mes detallados
                $carbonDate = Carbon::parse($sesion->fecha_sesion)->locale('es');
                $diaSemana = ucfirst($carbonDate->dayName);
                $diaMes = $carbonDate->format('d');
                $mes = $carbonDate->monthName;
                $anio = $carbonDate->format('Y');

                $formatoFecha = "{$diaSemana}, {$diaMes} de {$mes} del año {$anio}";

                return '<i class="bi bi-calendar-event me-1 text-secondary"></i> <span class="fw-semibold text-dark">' . $formatoFecha . '</span>';
            })
            ->addColumn('profesor_nombre', function ($sesion) {
                $nombre = trim(($sesion->profesor->user->name_users ?? '') . ' ' . ($sesion->profesor->user->last_name_users ?? ''));
                return '<span class="fw-bold text-dark">' . ($nombre ?: 'Sin asignar') . '</span>';
            })
            ->editColumn('observacion_sesion', function ($sesion) {
                $obs = $sesion->observacion_sesion ?? 'Sin observaciones';
                return '<span class="d-inline-block text-truncate" style="max-width: 280px;" title="' . e($obs) . '">' . e($obs) . '</span>';
            })
            // NUEVA COLUMNA: Estado de Asistencia
            ->addColumn('estado_asistencia', function ($sesion) {
                // withExists('asistencias') en la consulta nos crea este atributo booleano mágicamente
                if ($sesion->asistencias_exists) {
                    return '<span class="badge bg-success shadow-sm px-2 py-1">Registrada</span>';
                }
                return '<span class="badge bg-warning text-dark shadow-sm px-2 py-1">Pendiente</span>';
            })
            ->addColumn('action', function ($sesion) {
                return view('sesiones.partials.actions_por_seccion', compact('sesion'))->render();
            })
            // Agregamos 'estado_asistencia' para que parsee el HTML de los badges
            ->rawColumns(['fecha_sesion', 'profesor_nombre', 'observacion_sesion', 'estado_asistencia', 'action'])
            ->setRowId('id_sesiones');
    }

    public function query(Sesion $model): EloquentBuilder
    {
        // CORRECCIÓN APLICADA: select() se ejecuta ANTES de withExists()
        $query = $model->newQuery()
            ->select('sesiones.*') 
            ->with(['profesor.user'])
            ->withExists('asistencias') 
            ->where('id_seccion', $this->idSeccion);

        // LÓGICA DEL FILTRO
        if ($this->request()->filled('filtro_asistencia')) {
            $estado = $this->request()->get('filtro_asistencia');
            
            if ($estado === 'registrada') {
                $query->has('asistencias'); // Solo sesiones con asistencia
            } elseif ($estado === 'pendiente') {
                $query->doesntHave('asistencias'); // Solo sesiones sin asistencia
            }
        }

        return $query;
    }

    protected function getTableId(): string
    {
        return 'sesiones-seccion-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->ajax([
                'url' => route('clases.secciones.sesiones', $this->idSeccion),
                'data' => 'function(d) {
                    // Enviamos el valor del select al servidor en cada recarga
                    d.filtro_asistencia = $("#filtro_asistencia").val();
                }'
            ])
            ->orderBy(1, 'desc');
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('fecha_sesion')->title('Fecha de Clase'),
            Column::make('profesor_nombre')->title('Profesor Responsable')->searchable(false)->orderable(false),
            // Agregamos la nueva columna a la tabla
            Column::make('estado_asistencia')->title('Asistencia')->searchable(false)->orderable(false)->addClass('text-center align-middle'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(160)->addClass('text-center'),
        ];
    }
}