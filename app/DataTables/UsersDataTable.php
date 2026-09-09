<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class UsersDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('full_name', function ($user) {
                return $user->name_users . ' ' . $user->last_name_users;
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
            ->rawColumns(['action', 'status_users'])
            ->setRowId('id_users');
    }

    public function query(User $model): EloquentBuilder
    {
        return $model->newQuery()
            ->with(['rol', 'profesor.pnf', 'profesor.secciones'])
            ->selectRaw("*, CONCAT(name_users, ' ', last_name_users) as full_name");
    }

    protected function getTableId(): string
    {
        return 'users-table';
    }
    
    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
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