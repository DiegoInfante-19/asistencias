<?php

namespace App\DataTables;

use App\Models\Pnf;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class PnfsDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            // Badge visual estandarizado
            ->editColumn('vigencia_pnf', function ($pnf) {
                if ($pnf->vigencia_pnf) {
                    return '<span class="badge bg-success px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">Activo</span>';
                }
                return '<span class="badge bg-danger px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">Inactivo</span>';
            })
            ->addColumn('action', function ($pnf) {
                return view('pnfs.partials.actions', compact('pnf'))->render();
            })
            ->rawColumns(['vigencia_pnf', 'action'])
            ->setRowId('id_pnf');
    }

    public function query(Pnf $model): EloquentBuilder
    {
        return $model->newQuery()->select([
            'id_pnf',
            'nombre_pnf',
            'descripcion_pnf',
            'vigencia_pnf'
        ]);
    }

    protected function getTableId(): string
    {
        return 'pnfs-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('nombre_pnf')->title('Nombre del PNF'),
            Column::make('vigencia_pnf')->title('Estado')->addClass('text-center'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}