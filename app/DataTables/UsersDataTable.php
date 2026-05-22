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
        return EloquentDataTable::create($query)
            // 1. Agrega el contador automático
            ->addIndexColumn()

            // 2. Transforma el texto del estado en un Badge visual de Bootstrap
            ->editColumn('status', function ($user) {
                // Si es Activo muestra verde, de lo contrario muestra rojo/gris
                if (strtolower($user->status) === 'activo') {
                    return '<span class="badge bg-success">Activo</span>';
                }
                return '<span class="badge bg-danger">' . e($user->status) . '</span>';
            })

            // 3. Define los botones de acción usando una vista parcial limpia  
            ->addColumn('action', function ($user) {
                return view('profesores.partials.actions', compact('user'))->render();
            })

            // 4. Evita que el HTML de las acciones y el badge de estado se rompan por seguridad XSS
            ->rawColumns(['action', 'status']);
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

                // LA MAGIA DEL DISEÑO: Agrupamos 'l' (Mostrar registros) y 'B' (Botones) juntos a la izquierda
                'dom'        => '<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-3"lB>f>rtip',

                // Configuración de los botones con colores de Bootstrap 5
                'buttons'    => [
                    'dom' => [
                        'container' => [
                            'className' => 'dt-buttons d-flex gap-2'
                        ],
                        // EL TRUCO MAESTRO: Le decimos a DataTables que el botón base 
                        // solo debe usar la clase 'btn', eliminando la molesta 'dt-button'
                        'button' => [
                            'className' => 'btn' 
                        ]
                    ],
                    'buttons' => [
                        [
                            'extend'    => 'pdf',
                            'text'      => '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
                            'className' => 'btn btn-outline-secondary btn-tabla' // Ya no hace falta repetir 'btn' aquí
                        ],
                        [
                            'extend'    => 'excel',
                            'text'      => '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                            'className' => 'btn btn-outline-secondary btn-tabla    '
                        ],
                        [
                            'extend'    => 'print',
                            'text'      => '<i class="bi bi-printer me-1"></i> Imprimir',
                            'className' => 'btnbtn-outline-secondary btn-tabla'
                        ],
                        [
                            'extend'    => 'copy',
                            'text'      => '<i class="bi bi-clipboard me-1"></i> Copiar',
                            'className' => 'btn btn-outline-secondary btn-tabla'
                        ]
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

    /**
     * Columnas que se renderizarán en la interfaz.
     */
    protected function getColumns(): array
    {
        return [
            // Contador Automático (DT_RowIndex no permite ordenar ni buscar)
            Column::make('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false)
                ->width(40)
                ->addClass('text-center'),

            // Campos de la base de datos reestructurados
            Column::make('username')->title('Usuario')->addClass('all'),
            Column::make('name')->title('Nombres'),
            Column::make('last_name')->title('Apellidos'),
            Column::make('cedula')->title('Cédula'),
            Column::make('email')->title('Correo Electrónico'),
            Column::make('phone')->title('Teléfono'),

            // Columna de estado renderizada con Badges
            Column::make('status')->title('Estado')->addClass('text-center'),

            // Botones de acción siempre visibles en móvil
            Column::computed('action')
                ->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center all'),
        ];
    }
}


