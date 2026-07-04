<?php

namespace App\DataTables;

use App\Models\EstatusExpediente;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class EstatusExpedientesDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('action', function ($estatus) {
                return view('estatus_expedientes.partials.actions', compact('estatus'))->render();
            })
            ->rawColumns(['action'])
            ->setRowId('id_estatus_expediente');
    }

    public function query(EstatusExpediente $model): EloquentBuilder
    {
        return $model->newQuery()->select([
            'id_estatus_expediente',
            'nombre_estatus_expediente'
        ]);
    }

    protected function getTableId(): string
    {
        return 'estatus-expedientes-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('nombre_estatus_expediente')->title('Estatus del Expediente'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}