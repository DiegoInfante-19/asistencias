<?php

namespace App\DataTables;

use App\Models\PeriodoReceso;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class PeriodosRecesosDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->editColumn('fecha_inicio_periodo_receso', function ($periodo) {
                return $periodo->fecha_inicio_periodo_receso ? $periodo->fecha_inicio_periodo_receso->format('d/m/Y') : '';
            })
            ->editColumn('fecha_fin_periodo_receso', function ($periodo) {
                return $periodo->fecha_fin_periodo_receso ? $periodo->fecha_fin_periodo_receso->format('d/m/Y') : '';
            })
            // Distintivo visual estandarizado
            ->editColumn('suspension_actividades', function ($periodo) {
                if ($periodo->suspension_actividades) {
                    return '<span class="badge bg-danger px-3 py-2 shadow-sm" title="No hay clases/actividades" style="font-weight: 500; font-size: 0.9rem;">Sí</span>';
                }
                return '<span class="badge bg-success px-3 py-2 shadow-sm" title="Día hábil" style="font-weight: 500; font-size: 0.9rem;">No</span>';
            })
            ->addColumn('action', function ($periodo) {
                return view('periodos_recesos.partials.actions', compact('periodo'))->render();
            })
            ->rawColumns(['suspension_actividades', 'action'])
            ->setRowId('id_periodo_receso');
    }

    public function query(PeriodoReceso $model): EloquentBuilder
    {
        return $model->newQuery()->select([
            'id_periodo_receso',
            'nombre_periodo_receso',
            'fecha_inicio_periodo_receso',
            'fecha_fin_periodo_receso',
            'nivel_periodo_receso',
            'suspension_actividades'
        ]);
    }

    protected function getTableId(): string
    {
        return 'periodos-recesos-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder(); // <-- Hereda el diseño centralizado
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('nombre_periodo_receso')->title('Nombre / Evento'),
            Column::make('nivel_periodo_receso')->title('Tipo de Evento'),
            Column::make('fecha_inicio_periodo_receso')->title('Inicio')->addClass('text-center'),
            Column::make('fecha_fin_periodo_receso')->title('Fin')->addClass('text-center'),
            Column::make('suspension_actividades')->title('¿Suspende?')->addClass('text-center'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}