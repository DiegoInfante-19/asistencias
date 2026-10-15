<?php

namespace App\DataTables;

use App\Models\Profesor;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class ProfesorSeccionDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->editColumn('cedula', function ($profesor) {
                return '<span class="fw-bold">V-' . ltrim($profesor->user->cedula_users ?? '', 'V-') . '</span>';
            })
            ->addColumn('nombre_completo', function ($profesor) {
                $nombre = $profesor->user->name_users ?? '';
                $apellido = $profesor->user->last_name_users ?? '';
                $email = $profesor->user->email_users ?? '';

                return '<div class="fw-bold text-dark">' . $nombre . ' ' . $apellido . '</div>' .
                    '<div class="text-muted small">' . $email . '</div>';
            })
            ->editColumn('nivel_asignado', function ($profesor) {
                $nivel = $profesor->nivel_asignado?->value ?? $profesor->nivel_asignado ?? 'N/D';
                return '<h5><span class="badge bg-primary text-dark">' . $nivel . '</span></h5>';
            })
            ->filterColumn('cedula', function($query, $keyword) {
                $query->where('users.cedula_users', 'like', "%{$keyword}%");
            })
            ->filterColumn('nombre_completo', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->whereRaw("CONCAT(users.name_users, ' ', users.last_name_users) like ?", ["%{$keyword}%"])
                      ->orWhere('users.email_users', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('nivel_asignado', function($query, $keyword) {
                $query->where('profesores.nivel_asignado', 'like', "%{$keyword}%");
            })
            ->orderColumn('cedula', function ($query, $order) {
                $query->orderBy('users.cedula_users', $order);
            })
            ->orderColumn('nombre_completo', function ($query, $order) {
                $query->orderBy('users.name_users', $order)
                      ->orderBy('users.last_name_users', $order);
            })
            ->orderColumn('nivel_asignado', function ($query, $order) {
                $query->orderBy('profesores.nivel_asignado', $order);
            })
            ->addColumn('action', function ($profesor) {
                if (!$this->seccion->estaActiva()) {
                    return '<span class="badge bg-secondary">Bloqueado (Inactiva)</span>';
                }

                $seccionId = $this->seccion->id_seccion;
                $profesorId = $profesor->id_profesor;
                $url = route('secciones.remover-profesor', [$seccionId, $profesorId]);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return <<<HTML
        <form action="{$url}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de remover a este docente de la sección?');">
            {$csrf}
            {$method}
            <button type="submit" class="btn btn-danger shadow-sm">Remover</button>
        </form>
HTML;
            })
            ->rawColumns(['cedula', 'nombre_completo', 'nivel_asignado', 'action'])
            ->setRowId('id_profesor');
    }

    public function query(Profesor $model): EloquentBuilder
    {
        return $model->newQuery()
            ->select('profesores.*')
            ->join('users', 'profesores.id_users', '=', 'users.id_users')
            ->join('profesor_seccion', 'profesores.id_profesor', '=', 'profesor_seccion.id_profesor')
            ->with(['user'])
            ->where('profesor_seccion.id_seccion', $this->seccion->id_seccion);
    }

    protected function getTableId(): string
    {
        return 'docentes-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->ajax(route('secciones.show', ['seccion' => $this->seccion->id_seccion, 'table' => 'docentes-table']))
            ->orderBy(3);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            // Vinculamos explícitamente el name() para que Yajra sepa a qué columna física corresponde cada campo relacionado
            Column::make('cedula')->name('users.cedula_users')->title('Cédula')->searchable(true)->width('15%'),
            Column::make('nombre_completo')->name('users.name_users')->title('Nombre y Apellido')->searchable(true)->width('35%'),
            Column::make('nivel_asignado')->name('profesores.nivel_asignado')->title('Nivel Académico')->searchable(true)->width('40%'),
            Column::computed('action')->title('Acciones')->width('20%')->exportable(false)->printable(false)->addClass('text-center'),
        ];
    }
}