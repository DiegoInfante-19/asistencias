<?php

namespace App\DataTables;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class PersonasDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()

            // Nueva columna combinada: Nombres y Apellidos
            ->addColumn('full_name', function ($persona) {
                return $persona->nombres . ' ' . $persona->apellidos;
            })

            // Delegamos los botones a una vista parcial como en los otros módulos
            ->addColumn('action', function ($persona) {
                return view('personas.partials.actions', compact('persona'))->render();
            })
            ->rawColumns(['action'])
            ->setRowId('id_personas');
    }

    public function query(Persona $model): EloquentBuilder
    {
        // Concatenamos para que el buscador global de DataTables encuentre por nombre completo
        return $model->newQuery()
            ->selectRaw("*, CONCAT(nombres, ' ', apellidos) as full_name");
    }

    protected function getTableId(): string
    {
        return 'personas-table';
    }

    public function html(): HtmlBuilder
    {
        // Reutilizamos toda la configuración, botones, DOM y traducciones del BaseDataTable
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('cedula')->title('Cédula'),
            Column::make('full_name')->title('Nombre y Apellido')->searchable(true),
            Column::make('correo_electronico')->title('Correo Electrónico'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center all'),
        ];
    }
}
