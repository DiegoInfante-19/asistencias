<?php

namespace App\DataTables;

use App\Models\Cohorte;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class CohortesDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('periodo', function ($cohorte) {
                $periodo = $cohorte->periodosAcademicos->first();
                if ($periodo && $periodo->fecha_inicio && $periodo->fecha_fin) {
                    return $periodo->fecha_inicio->format('Y') . ' - ' . $periodo->fecha_fin->format('Y');
                }
                return '<span class="text-muted small">Sin período</span>';
            })
            ->addColumn('n_secciones', function ($cohorte) {
                $periodo = $cohorte->periodosAcademicos->first();
                $count = $periodo ? $periodo->secciones->count() : 0;
                return $count > 0 
                    ? '<span class="fw-bold text-primary fs-6">' . $count . '</span>' 
                    : '<span class="text-muted small fst-italic">0</span>';
            })
            ->addColumn('n_estudiantes', function ($cohorte) {
                $count = $cohorte->personas->count();
                return $count > 0 
                    ? '<span class="fw-bold text-primary fs-6">' . $count . '</span>' 
                    : '<span class="text-muted small fst-italic">0</span>';
            })
            ->editColumn('estatus_cohorte', function ($cohorte) {
                $estatus = strtolower(trim($cohorte->estatus_cohorte));
                switch ($estatus) {
                    case 'activo':
                    case 'en curso':
                        $badgeClass = 'bg-success';
                        break;
                    case 'proxima':
                    case 'próxima':
                        $badgeClass = 'bg-info text-dark';
                        break;
                    default:
                        $badgeClass = 'bg-secondary';
                        break;
                }
                return '<span class="badge ' . $badgeClass . ' px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">' . ucfirst($cohorte->estatus_cohorte) . '</span>';
            })
            ->addColumn('action', function ($cohorte) {
                // AQUÍ ESTÁ EL CAMBIO CLAVE: Apuntamos a la nueva ruta
                return view('estructura_academica.partials.actions', compact('cohorte'))->render();
            })
            ->rawColumns(['periodo', 'n_secciones', 'n_estudiantes', 'estatus_cohorte', 'action'])
            ->setRowId('id_cohortes');
    }

    public function query(Cohorte $model): EloquentBuilder
    {
        return $model->newQuery()
            ->with(['periodosAcademicos.secciones', 'personas'])
            ->select([
                'id_cohortes',
                'numero_cohorte',
                'descripcion_cohorte',
                'estatus_cohorte'
            ]);
    }

    protected function getTableId(): string
    {
        return 'cohortes-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->ajax([
                'url' => route('estructura.index'),
            ])
            ->orderBy(1, 'asc')
            ->buttons([
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel')->addClass('btn btn-success btn-sm shadow-sm'),
                Button::make('pdf')->text('<i class="bi bi-file-earmark-pdf"></i> PDF')->addClass('btn btn-danger btn-sm shadow-sm'),
                Button::make('print')->text('<i class="bi bi-printer"></i> Imprimir')->addClass('btn btn-secondary btn-sm shadow-sm'),
                Button::make('colvis')->text('<i class="bi bi-layout-three-columns me-1"></i> Columnas')->addClass('btn btn-outline-secondary btn-sm shadow-sm')
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center align-middle'),
            Column::make('numero_cohorte')->title('Cohorte')->addClass('text-center fw-bold align-middle')->width('15%'),
            Column::make('periodo')->title('Período')->addClass('text-center align-middle')->searchable(false)->width('15%'),
            Column::make('estatus_cohorte')->title('Estatus')->addClass('text-center align-middle')->width('15%'),
            Column::make('n_secciones')->title('N° Secciones')->addClass('text-center align-middle')->searchable(false)->orderable(false)->width('15%'),
            Column::make('n_estudiantes')->title('N° Estudiantes')->addClass('text-center align-middle')->searchable(false)->orderable(false)->width('15%'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width('25%')->addClass('text-center align-middle'),
        ];
    }
}