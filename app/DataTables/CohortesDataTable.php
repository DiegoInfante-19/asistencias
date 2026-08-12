<?php

namespace App\DataTables;

use App\Models\Cohorte;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class CohortesDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            // Distintivo visual mejorado según el estatus de la cohorte
            ->editColumn('estatus_cohorte', function ($cohorte) {
                $estatus = strtolower(trim($cohorte->estatus_cohorte));
                
                switch ($estatus) {
                    case 'activo':
                    case 'en curso':
                        $badgeClass = 'bg-success text-white';
                        $icono = '<i class="bi bi-check-circle-fill me-1"></i>';
                        break;
                    case 'proxima':
                    case 'próxima':
                        $badgeClass = 'bg-info text-dark';
                        $icono = '<i class="bi bi-clock-fill me-1"></i>';
                        break;
                    case 'finalizada':
                    case 'finalizado':
                    default:
                        $badgeClass = 'bg-secondary text-white';
                        $icono = '<i class="bi bi-archive-fill me-1"></i>';
                        break;
                }

                return '<span class="badge ' . $badgeClass . ' px-3 py-2 shadow-sm">' . $icono . ucfirst($cohorte->estatus_cohorte) . '</span>';
            })
            ->addColumn('action', function ($cohorte) {
                return view('cohortes.partials.actions', compact('cohorte'))->render();
            })
            ->rawColumns(['estatus_cohorte', 'action'])
            ->setRowId('id_cohortes');
    }

    public function query(Cohorte $model): EloquentBuilder
    {
        return $model->newQuery()->select([
            'id_cohortes',
            'numero_cohorte',
            'fecha_inicio_cohorte',
            'fecha_fin_cohorte',
            'estatus_cohorte'
        ]);
    }

    protected function getTableId(): string
    {
        return 'cohortes-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('numero_cohorte')->title('Número'),
            Column::make('fecha_inicio_cohorte')->title('Inicio'),
            Column::make('fecha_fin_cohorte')->title('Fin'),
            Column::make('estatus_cohorte')->title('Estatus')->addClass('text-center'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}