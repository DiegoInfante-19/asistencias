<?php

namespace App\DataTables;

use App\Models\Cargo;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class CargosDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('action', function ($cargo) {
                return view('cargos.partials.actions', compact('cargo'))->render();
            })
            ->rawColumns(['action'])
            ->setRowId('id_cargo');
    }

    public function query(Cargo $model): EloquentBuilder
    {
        return $model->newQuery()->select([
            'id_cargo',
            'descripcion_cargo'
        ]);
    }

    protected function getTableId(): string
    {
        return 'cargos-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('descripcion_cargo')->title('Descripción del Cargo'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}