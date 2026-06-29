<?php

namespace App\DataTables;

use App\Models\Estado;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class EstadosDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('action', function ($estado) {
                return view('localidades.partials.actions_estado', compact('estado'))->render();
            })
            ->rawColumns(['action']);
    }

    public function query(Estado $model): EloquentBuilder
    {
        return $model->newQuery();
    }

    protected function getTableId(): string
    {
        return 'estados-table';
    }
    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('nombre_estado')->title('Nombre del Estado'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}
