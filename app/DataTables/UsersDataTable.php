<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;

class UsersDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('full_name', function ($user) {
                return e($user->name_users . ' ' . $user->last_name_users);
            })
            // NUEVA COLUMNA: PNF Asignado al Profesor
            ->addColumn('pnf_nombre', function ($user) {
                if ($user->profesor && $user->profesor->pnf) {
                    return '<span class="badge text-dark bg-dark-subtle border border-dark rounded-3 shadow-sm px-2 py-1" style="font-size: 0.8rem; font-weight: 500;">' . e($user->profesor->pnf->nombre_pnf) . '</span>';
                }
                return '<span class="text-muted small fst-italic">Sin PNF asignado</span>';
            })
            // NUEVA COLUMNA: Secciones Registradas/Asignadas
            ->addColumn('secciones_nombres', function ($user) {
                if (!$user->profesor || $user->profesor->secciones->isEmpty()) {
                    return '<span class="text-muted small fst-italic">Sin secciones</span>';
                }

                $secciones = $user->profesor->secciones;
                $cantidad = $secciones->count();

                if ($cantidad <= 4) {
                    $badges = $secciones->map(function ($sec) {
                        return '<span class="badge bg-light text-dark border shadow-sm me-1 mb-1" style="font-size: 0.78rem;">' . e($sec->nombre_seccion) . '</span>';
                    })->implode('');

                    return '<div class="d-flex flex-wrap gap-1 align-items-center">' . $badges . '</div>';
                }

                return '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-bold">' . $cantidad . ' Secciones</span>';
            })
            // Badge estandarizado visualmente
            ->editColumn('status_users', function ($user) {
                if (strtolower(trim($user->status_users)) === 'activo') {
                    return '<span class="badge bg-success px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">Activo</span>';
                }
                return '<span class="badge bg-danger px-3 py-2 shadow-sm" style="font-weight: 500; font-size: 0.9rem;">' . ucfirst(e($user->status_users)) . '</span>';
            })
            ->addColumn('action', function ($user) {
                return view('profesores.partials.actions', compact('user'))->render();
            })
            ->filterColumn('full_name', function($query, $keyword) {
                // Permite buscar por cédula O por la concatenación del nombre y apellido
                $query->where(function($q) use ($keyword) {
                    $q->where('cedula_users', 'like', "%{$keyword}%")
                      ->orWhereRaw("CONCAT(name_users, ' ', last_name_users) like ?", ["%{$keyword}%"]);
                });
            })
            // Búsqueda personalizada por PNF
            ->filterColumn('pnf_nombre', function($query, $keyword) {
                $query->whereHas('profesor.pnf', function($q) use ($keyword) {
                    $q->where('nombre_pnf', 'like', "%{$keyword}%");
                });
            })
            // Búsqueda personalizada por Secciones
            ->filterColumn('secciones_nombres', function($query, $keyword) {
                $query->whereHas('profesor.secciones', function($q) use ($keyword) {
                    $q->where('nombre_seccion', 'like', "%{$keyword}%");
                });
            })
            ->filter(function ($query) {
                // 1. Filtro por Rol
                if (request()->has('filtro_rol') && !empty(request()->get('filtro_rol'))) {
                    $query->where('id_rol', request()->get('filtro_rol'));
                }

                // 2. Filtro por Estatus
                if (request()->has('filtro_estatus') && !empty(request()->get('filtro_estatus'))) {
                    $query->where('status_users', request()->get('filtro_estatus'));
                }

                // 3. Filtro por PNF específico
                if (request()->has('filtro_pnf') && !empty(request()->get('filtro_pnf'))) {
                    $idPnf = request()->get('filtro_pnf');
                    $query->whereHas('profesor', function ($subq) use ($idPnf) {
                        $subq->where('id_pnf', $idPnf);
                    });
                }

                // 4. Filtro por Nivel Académico del PNF asignado (TSU o Ingeniería)
                if (request()->has('filtro_nivel') && !empty(request()->get('filtro_nivel'))) {
                    $nivelAsignado = request()->get('filtro_nivel');
                    $query->whereHas('profesor', function ($subq) use ($nivelAsignado) {
                        $subq->where('nivel_asignado', $nivelAsignado);
                    });
                }

                // 5. Filtro por Sección específica
                if (request()->has('filtro_seccion') && !empty(request()->get('filtro_seccion'))) {
                    $idSeccion = request()->get('filtro_seccion');
                    $query->whereHas('profesor.secciones', function ($subq) use ($idSeccion) {
                        $subq->where('secciones.id_seccion', $idSeccion);
                    });
                }

                // 6. Filtro por Sección Activa
                if (request()->has('filtro_seccion_activa') && request()->get('filtro_seccion_activa') !== '') {
                    $query->seccionActiva(request()->get('filtro_seccion_activa'));
                }

                // 7. Filtro Booleano: Tiene PNF
                if (request()->has('filtro_tiene_pnf') && request()->get('filtro_tiene_pnf') !== '') {
                    $query->tienePnf(request()->get('filtro_tiene_pnf'));
                }

                // 8. Filtro Booleano: Tiene Sección
                if (request()->has('filtro_tiene_seccion') && request()->get('filtro_tiene_seccion') !== '') {
                    $query->tieneSeccion(request()->get('filtro_tiene_seccion'));
                }
            }, true)
            ->rawColumns(['action', 'status_users', 'pnf_nombre', 'secciones_nombres'])
            ->setRowId('id_users');
    }

    public function query(User $model): EloquentBuilder
    {
        return $model->newQuery()
            // Blindaje: Solo traemos a los usuarios que tengan el rol de Profesor
            ->where('id_rol', User::ROLE_PROFESOR)
            ->with([
                'rol', 
                'profesor.pnf', 
                'profesor.secciones'
            ])
            ->selectRaw("*, CONCAT(name_users, ' ', last_name_users) as full_name");
    }

    protected function getTableId(): string
    {
        return 'users-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2, 'asc')
            ->buttons([
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel')->addClass('btn btn-success btn-sm shadow-sm'),
                Button::make('pdf')->text('<i class="bi bi-file-earmark-pdf"></i> PDF')->addClass('btn btn-danger btn-sm shadow-sm'),
                Button::make('print')->text('<i class="bi bi-printer"></i> Imprimir')->addClass('btn btn-secondary btn-sm shadow-sm'),
                Button::make('colvis')->text('<i class="bi bi-layout-three-columns me-1"></i> Columnas')->addClass('btn btn-outline-secondary btn-sm shadow-sm')
            ])
            ->addTableClass('table-sm align-middle')
            ->parameters([
                'responsive' => true,
                'autoWidth'  => false,
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center align-middle'),
            Column::make('cedula_users')->title('Cédula')->width(110)->addClass('align-middle'),
            Column::make('full_name')->title('Nombre y Apellido')->searchable(true)->addClass('align-middle fw-bold'),
            Column::make('pnf_nombre')->title('PNF')->searchable(true)->addClass('align-middle'),
            Column::make('secciones_nombres')->title('Secciones')->searchable(true)->addClass('align-middle'),
            Column::make('status_users')->title('Estado')->addClass('text-center align-middle'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(120)->addClass('text-center align-middle all'),
        ];
    }
}