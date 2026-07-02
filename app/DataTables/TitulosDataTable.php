<?php

namespace App\DataTables;

use App\Models\Titulo;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class TitulosDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('action', function ($titulo) {
                return view('titulos.partials.actions', compact('titulo'))->render();
            })
            ->rawColumns(['action'])
            ->setRowId('id_titulos');
    }

    public function query(Titulo $model): EloquentBuilder
    {
        return $model->newQuery()->select([
            'id_titulos', 
            'nombre_titulo_base', 
            'nivel_academico'
        ]);
    }

    protected function getTableId(): string
    {
        return 'titulos-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('nombre_titulo_base')->title('Título'),
            Column::make('nivel_academico')->title('Nivel Académico'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }
}