<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Procesa las filas de la tabla antes de enviarlas al frontend.
     */
    public function dataTable($query): EloquentDataTable
    {
        // Usamos EloquentDataTable::create($query) para que Yajra resuelva el mapeo correctamente
        return EloquentDataTable::create($query)
            // Define los botones de acción usando una vista parcial limpia  
            ->addColumn('action', function ($user) {
                return view('profesores.partials.actions', compact('user'))->render();
            })
            // Formatea la fecha de creación
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('d/m/Y H:i');
            })
            // Evita que se rompan los botones HTML, manteniendo el resto seguro contra XSS
            ->rawColumns(['action']);
    }

    /**
     * Consulta base de Eloquent.
     */
    public function query(User $model): EloquentBuilder
    {
        return $model->newQuery()->with('role');
    }

    /**
     * Configuración estructural y estética de la tabla (Bootstrap 5).
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->parameters([
                'responsive' => true,
                'autoWidth'  => false,
                'language'   => [
                    'processing'     => 'Procesando...',
                    'search'         => 'Buscar:',
                    'lengthMenu'     => 'Mostrar _MENU_ registros',
                    'info'           => 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                    'infoEmpty'      => 'Mostrando registros del 0 al 0 de un total de 0 registros',
                    'infoFiltered'   => '(filtrado de un total de _MAX_ registros)',
                    'loadingRecords' => 'Cargando aplicaciones...',
                    'zeroRecords'    => 'No se encontraron resultados',
                    'emptyTable'     => 'Ningún dato disponible en esta tabla',
                    'paginate'       => [
                        'first'    => 'Primero',
                        'previous' => 'Anterior',
                        'next'     => 'Siguiente',
                        'last'     => 'Último'
                    ],
                    'aria' => [
                        'sortAscending'  => ': Activar para ordenar la columna de manera ascendente',
                        'sortDescending' => ': Activar para ordenar la columna de manera descendente'
                    ]
                ]
            ]);
    }
    /**
     * Columnas que se renderizarán en la interfaz.
     */
    protected function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(50),
            Column::make('name')->title('Nombre Completo'),
            Column::make('email')->title('Correo Electrónico'),
            Column::make('created_at')->title('Fecha Registro'),
            Column::computed('action')
                ->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }
}
