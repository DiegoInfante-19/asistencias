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
                // Configuramos Carbon en español para que devuelva el día y mes correctos
                $carbonDate = Carbon::parse($sesion->fecha_sesion)->locale('es');
                $diaSemana = ucfirst($carbonDate->dayName); // Miércoles, Lunes, etc.
                $diaMes = $carbonDate->format('d'); // 10, 23, etc.
                $mes = $carbonDate->monthName; // noviembre, abril, etc.
                $anio = $carbonDate->format('Y'); // 0000

                $formatoFecha = "{$diaSemana}, {$diaMes} de {$mes} del año {$anio}";

                return '<i class="bi bi-calendar-event me-1 text-secondary"></i> <span class="fw-semibold text-dark">' . $formatoFecha . '</span>';
            })
            ->addColumn('profesor_cargo', function ($sesion) {
                $nombre = $sesion->profesor->user->name_users ?? 'No asignado';
                $apellido = $sesion->profesor->user->last_name_users ?? '';
                return trim($nombre . ' ' . $apellido);
            })
            ->addColumn('action', function ($sesion) {
                return view('secciones.partials.actions_sesiones', compact('sesion'))->render();
            })
            ->rawColumns(['fecha_sesion', 'action'])
            ->setRowId('id_sesiones');
    }

    public function query(Sesion $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['profesor.user'])
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
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}