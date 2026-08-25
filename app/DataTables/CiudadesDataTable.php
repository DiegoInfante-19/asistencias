<?php

namespace App\DataTables;

use App\Models\Ciudad;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class CiudadesDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('action', function ($ciudad) {
                return view('localidades.partials.actions_ciudad', compact('ciudad'))->render();
            })
            ->rawColumns(['action'])
            ->setRowId('id_ciudad');
    }

    public function query(Ciudad $model): EloquentBuilder
    {
        return $model->newQuery()
            ->join('estados', 'ciudades.id_estado', '=', 'estados.id_estado')
            ->select([
                'ciudades.id_ciudad',
                'ciudades.id_estado', 
                'ciudades.nombre_ciudad',
                'estados.nombre_estado as estado_nombre'
            ]);
    }

    protected function getTableId(): string
    {
        return 'ciudades-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->ajax(['url' => route('localidades.index', ['table' => 'ciudades'])]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('estado_nombre')->title('Estado')->name('estados.nombre_estado'), 
            Column::make('nombre_ciudad')->title('Nombre de la Ciudad'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}