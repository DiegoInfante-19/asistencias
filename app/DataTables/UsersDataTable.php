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
            ->rawColumns(['action', 'status_users']);
    }

    public function query(User $model): EloquentBuilder
    {
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