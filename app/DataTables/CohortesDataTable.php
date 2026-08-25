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
            // Badges sólidos con tamaño de fuente aumentado y legibles
            ->editColumn('estatus_cohorte', function ($cohorte) {
                $estatus = strtolower(trim($cohorte->estatus_cohorte));
                
                switch ($estatus) {
                    case 'activo':
                    case 'en curso':
                        $badgeClass = 'bg-success text-white';
                        break;
                    case 'proxima':
                    case 'próxima':
                        $badgeClass = 'bg-info text-dark';
                        break;
                    case 'finalizada':
                    case 'finalizado':
                    default:
                        $badgeClass = 'bg-secondary text-white';
                        break;
                }

                return '<span class="badge ' . $badgeClass . ' px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">' . ucfirst($cohorte->estatus_cohorte) . '</span>';
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
                'url' => route('cohortes.index'),
                'type' => 'GET',
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'X-CSRF-TOKEN' => csrf_token()
                ]
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('numero_cohorte')->title('Número'),
            Column::make('descripcion_cohorte')->title('Descripción'),
            Column::make('estatus_cohorte')->title('Estatus')->addClass('text-center'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}