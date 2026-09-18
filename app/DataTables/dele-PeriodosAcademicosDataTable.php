<?php

namespace App\DataTables;

use App\Models\PeriodoAcademico;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class PeriodosAcademicosDataTable extends DataTable
{
    /**
     * Construye el DataTable procesando la consulta.
     * Aquí se inyecta el HTML para los badges, fechas y botones de acción.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('cohorte_vinculada', function ($periodo) {
                // Formateamos visualmente el dato que viene de la relación
                $numero = $periodo->cohorte->numero_cohorte ?? 'N/D';
                return '<span class="fw-bold text-dark">Cohorte ' . $numero . '</span>';
            })
            ->editColumn('fecha_inicio', function ($periodo) {
                return Carbon::parse($periodo->fecha_inicio)->format('d/m/Y');
            })
            ->editColumn('fecha_fin', function ($periodo) {
                return Carbon::parse($periodo->fecha_fin)->format('d/m/Y');
            })
            ->editColumn('estatus_periodo', function ($periodo) {
                // Renderizado dinámico de Badges de Bootstrap 5
                if (strtolower($periodo->estatus_periodo) === 'activo') {
                    return '<span class="badge bg-success px-3 py-2 shadow-sm" style="font-weight: 500;">Activo</span>';
                } elseif (strtolower($periodo->estatus_periodo) === 'cerrado') {
                    return '<span class="badge bg-danger px-3 py-2 shadow-sm" style="font-weight: 500;">Cerrado</span>';
                }
                return '<span class="badge bg-secondary px-3 py-2 shadow-sm" style="font-weight: 500;">Inactivo</span>';
            })
            ->addColumn('action', function ($periodo) {
                // Retornamos la vista parcial con los botones de Editar y Eliminar
                return view('periodos_academicos.partials.actions', compact('periodo'))->render();
            })
            // Habilitamos la renderización de HTML crudo en estas columnas
            ->rawColumns(['cohorte_vinculada', 'estatus_periodo', 'action'])
            ->setRowId('id_periodo');
    }

    /**
     * Obtiene la consulta inicial con las relaciones necesarias cargadas (Eager Loading).
     */
    public function query(PeriodoAcademico $model): QueryBuilder
    {
        // Traemos la relación 'cohorte' para evitar el problema N+1 consultas
        return $model->newQuery()->with('cohorte');
    }

    /**
     * Configuraciones del constructor HTML del DataTable (UI y Scripts).
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('periodos-academicos-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    // DOM optimizado para Bootstrap 5
                    ->dom("<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" .
                          "<'row'<'col-sm-12'tr>>" .
                          "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>")
                    ->orderBy(0, 'desc') // Ordenar por ID descendente por defecto
                    ->parameters([
                        'responsive' => true,
                        'autoWidth'  => false,
                        'language'   => [
                            'url' => '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                        ],
                    ]);
    }

    /**
     * Define las columnas que se mostrarán en la vista.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id_periodo')->title('ID')->width('50px')->addClass('text-center'),
            Column::make('cohorte_vinculada')->title('Cohorte Base')->name('cohorte.numero_cohorte'),
            Column::make('fecha_inicio')->title('Fecha Apertura')->addClass('text-center'),
            Column::make('fecha_fin')->title('Fecha Cierre')->addClass('text-center'),
            Column::make('estatus_periodo')->title('Estatus')->addClass('text-center'),
            Column::computed('action')
                  ->title('Acciones')
                  ->exportable(false)
                  ->printable(false)
                  ->width('120px')
                  ->addClass('text-center align-middle'),
        ];
    }

    /**
     * Nombre del archivo al exportar.
     */
    protected function filename(): string
    {
        return 'PeriodosAcademicos_' . date('YmdHis');
    }
}