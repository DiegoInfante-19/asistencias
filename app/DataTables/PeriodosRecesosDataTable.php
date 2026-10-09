<?php

namespace App\DataTables;

use App\Models\PeriodoReceso;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Carbon\Carbon;

class PeriodosRecesosDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            
            ->addColumn('fecha', function ($periodo) {
                $inicio = $periodo->fecha_inicio_periodo_receso;
                $fin = $periodo->fecha_fin_periodo_receso;

                if (!$inicio || !$fin) return 'Fechas no definidas';

                $inicio->locale('es');
                $fin->locale('es');

                if ($inicio->isSameDay($fin)) {
                    return ucfirst($inicio->isoFormat('D [de] MMMM [de] YYYY'));
                }

                if ($inicio->isSameMonth($fin) && $inicio->isSameYear($fin)) {
                    return 'Del ' . $inicio->format('d') . ' al ' . $fin->isoFormat('D [de] MMMM [de] YYYY');
                }

                if ($inicio->isSameYear($fin)) {
                    return 'Del ' . $inicio->isoFormat('D [de] MMMM') . ' al ' . $fin->isoFormat('D [de] MMMM [de] YYYY');
                }

                return 'Del ' . $inicio->isoFormat('D [de] MMMM [de] YYYY') . ' al ' . $fin->isoFormat('D [de] MMMM [de] YYYY');
            })
            
            ->addColumn('action', function ($periodo) {
                return view('periodos_recesos.partials.actions', compact('periodo'))->render();
            })
            ->rawColumns(['action'])
            ->setRowId('id_periodo_receso');
    }

   public function query(PeriodoReceso $model): EloquentBuilder
    {
        return $model->newQuery()->select([
            'id_periodo_receso',
            'nombre_periodo_receso',
            'fecha_inicio_periodo_receso',
            'fecha_fin_periodo_receso',
            'nivel_periodo_receso',       // <--- ¡Añadido aquí!
            'descripcion_periodo_receso', // <--- Añadido para asegurar datos completos
            'suspension_actividades'      // <--- Añadido para asegurar datos completos
        ]);
    }

    protected function getTableId(): string
    {
        return 'periodos-recesos-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width('10%')->addClass('text-center'),
            Column::make('nombre_periodo_receso')->title('Nombre / Evento')->width('35%'),
            Column::computed('fecha')->title('Fecha del Evento')->width('50%'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width('15%')->addClass('text-center'),
        ];
    }
}