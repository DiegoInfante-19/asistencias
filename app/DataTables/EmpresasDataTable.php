<?php

namespace App\DataTables;

use App\Models\Empresa;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class EmpresasDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('action', function ($empresa) {
                return view('empresas.partials.actions', compact('empresa'))->render();
            })
            ->rawColumns(['action'])
            ->setRowId('id_empresa');
    }

    public function query(Empresa $model): EloquentBuilder
    {
        return $model->newQuery()->select([
            'empresas.id_empresa',
            'empresas.nombre_empresa'
        ]);
    }

    protected function getTableId(): string
    {
        return 'empresas-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('nombre_empresa')->title('Nombre de la Empresa'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}