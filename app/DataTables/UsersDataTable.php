<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends BaseDataTable
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
