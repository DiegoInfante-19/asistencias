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
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            
            // Nueva columna combinada: Nombre y Apellido
            ->addColumn('full_name', function ($user) {
                return $user->name_users . ' ' . $user->last_name_users;
            })

            ->editColumn('status_users', function ($user) {
                if (strtolower($user->status_users) === 'activo') {
                    return '<span class="badge bg-success">Activo</span>';
                }
                return '<span class="badge bg-danger">' . e($user->status_users) . '</span>';
            })
            ->addColumn('action', function ($user) {
                return view('profesores.partials.actions', compact('user'))->render();
            })
            ->rawColumns(['action', 'status_users']);
    }

    public function query(User $model): EloquentBuilder
    {
        // Concatenamos para que el buscador de DataTables encuentre por nombre completo
        return $model->newQuery()
            ->with('rol')
            ->selectRaw("*, CONCAT(name_users, ' ', last_name_users) as full_name");
    }

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
                'dom'        => '<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-3"lB>f>rtip',
                'buttons'    => [
                    'dom' => [
                        'container' => ['className' => 'dt-buttons d-flex gap-2'],
                        'button' => ['className' => 'btn']
                    ],
                    'buttons' => [
                        ['extend' => 'pdf', 'text' => '<i class="bi bi-file-earmark-pdf me-1"></i> PDF', 'className' => 'btn btn-outline-secondary btn-tabla'],
                        ['extend' => 'excel', 'text' => '<i class="bi bi-file-earmark-excel me-1"></i> Excel', 'className' => 'btn btn-outline-secondary btn-tabla'],
                        ['extend' => 'print', 'text' => '<i class="bi bi-printer me-1"></i> Imprimir', 'className' => 'btn btn-outline-secondary btn-tabla'],
                        ['extend' => 'copy', 'text' => '<i class="bi bi-clipboard me-1"></i> Copiar', 'className' => 'btn btn-outline-secondary btn-tabla']
                    ]
                ],
                'language'   => [
                    'processing'     => 'Procesando...',
                    'search'         => '<b>Buscar:</b>',
                    'lengthMenu'     => '<b>Mostrar _MENU_ registros</b>',
                    'info'           => 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                    'infoEmpty'      => 'Mostrando registros del 0 al 0 de un total de 0 registros',
                    'infoFiltered'   => '(filtrado de un total de _MAX_ registros)',
                    'loadingRecords' => 'Cargando registros...',
                    'zeroRecords'    => 'No se encontraron resultados',
                    'emptyTable'     => 'Ningún dato disponible en esta tabla',
                    'paginate' => [
                        'first'    => '<i class="bi bi-chevron-double-left"></i>',
                        'previous' => '<i class="bi bi-chevron-left"></i> Anterior',
                        'next'     => 'Siguiente <i class="bi bi-chevron-right"></i>',
                        'last'     => '<i class="bi bi-chevron-double-right"></i>'
                    ]
                ]
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('cedula_users')->title('Cédula'),
            Column::make('full_name')->title('Nombre y Apellido')->searchable(true),
            Column::make('email_users')->title('Correo Electrónico'),
            Column::make('username')->title('Usuario')->addClass('all'),
            Column::make('status_users')->title('Estado')->addClass('text-center'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center all'),
        ];
    }
}