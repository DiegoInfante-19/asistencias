<?php

namespace App\DataTables;

use App\Models\PeriodoReceso;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Carbon\Carbon; // Aseguramos importar Carbon

class PeriodosRecesosDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            
            // Nueva columna calculada con lógica "humana"
            ->addColumn('fecha', function ($periodo) {
                $inicio = $periodo->fecha_inicio_periodo_receso;
                $fin = $periodo->fecha_fin_periodo_receso;

                if (!$inicio || !$fin) return 'Fechas no definidas';

                // Forzamos el idioma a español
                $inicio->locale('es');
                $fin->locale('es');

                // CASO 1: El evento es de un solo día
                if ($inicio->isSameDay($fin)) {
                    // Ej: 15 de octubre de 2026
                    return ucfirst($inicio->isoFormat('D [de] MMMM [de] YYYY'));
                }

                // CASO 2: Empieza y termina en distintos días, pero el MISMO mes y año
                if ($inicio->isSameMonth($fin) && $inicio->isSameYear($fin)) {
                    // Ej: Del 15 al 20 de octubre de 2026
                    return 'Del ' . $inicio->format('d') . ' al ' . $fin->isoFormat('D [de] MMMM [de] YYYY');
                }

                // CASO 3: Empieza y termina en distintos meses, pero el MISMO año
                if ($inicio->isSameYear($fin)) {
                    // Ej: Del 15 de octubre al 02 de noviembre de 2026
                    return 'Del ' . $inicio->isoFormat('D [de] MMMM') . ' al ' . $fin->isoFormat('D [de] MMMM [de] YYYY');
                }

                // CASO 4: Empieza y termina en distintos años
                // Ej: Del 20 de diciembre de 2026 al 15 de enero de 2027
                return 'Del ' . $inicio->isoFormat('D [de] MMMM [de] YYYY') . ' al ' . $fin->isoFormat('D [de] MMMM [de] YYYY');
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
        // IMPORTANTE: Mantenemos fecha_inicio y fecha_fin en el select para que Carbon las pueda usar arriba
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
        return $this->sharedHtmlBuilder(); // Hereda el diseño centralizado
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('nombre_periodo_receso')->title('Nombre / Evento'),
            Column::make('nivel_periodo_receso')->title('Tipo de Evento'),
            
            // Reemplazamos fecha_inicio y fecha_fin por la nueva columna computada
            Column::computed('fecha')->title('Fecha del Evento')->addClass('text-center'),
            
            Column::make('suspension_actividades')->title('¿Suspende?')->addClass('text-center'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}