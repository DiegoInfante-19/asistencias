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
            ->addColumn('action', function ($sesion) {
                return view('sesiones.partials.actions_por_seccion', compact('sesion'))->render();
            })
            ->rawColumns(['fecha_sesion', 'profesor_nombre', 'observacion_sesion', 'action'])
            ->setRowId('id_sesiones');
    }

    public function query(Sesion $model): EloquentBuilder
    {
        return $model->newQuery()
            ->with(['profesor.user'])
            ->where('id_seccion', $this->idSeccion)
            ->select('sesiones.*');
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
            ])
            ->orderBy(1, 'desc');
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('fecha_sesion')->title('Fecha de Clase'),
            Column::make('profesor_nombre')->title('Profesor Responsable')->searchable(false)->orderable(false),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(160)->addClass('text-center'),
        ];
    }
}