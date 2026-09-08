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
                // Si tu Enum es backed (ej. string/int), usa ->value o caséalo a string. 
                // Si es un Enum nativo de PHP, puedes usar $profesor->nivel_asignado->value o ->name.
                $nivel = $profesor->nivel_asignado?->value ?? $profesor->nivel_asignado ?? 'N/D';
                return '<span class="badge bg-info text-dark">' . $nivel . '</span>';
            })
            ->addColumn('pnf_base', function ($profesor) {
                return $profesor->pnf->nombre_pnf ?? 'Sin PNF';
            })
            ->addColumn('action', function ($profesor) {
                $seccionId = $this->seccion->id_seccion;
                $profesorId = $profesor->id_profesor;
                $url = route('secciones.remover-profesor', [$seccionId, $profesorId]);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return <<<HTML
                <form action="{$url}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de remover a este docente de la sección?');">
                    {$csrf}
                    {$method}
                    <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                </form>
HTML;
            })
            ->rawColumns(['cedula', 'nombre_completo', 'nivel_asignado', 'pnf_base', 'action'])
            ->setRowId('id_profesor');
    }

    public function query(Profesor $model): EloquentBuilder
    {
        return $model->newQuery()
            ->with(['user', 'pnf'])
            ->whereHas('secciones', function ($q) {
                $q->where('secciones.id_seccion', $this->seccion->id_seccion);
            });
    }

    protected function getTableId(): string
    {
        return 'docentes-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->ajax([
                'url' => route('secciones.show', ['seccion' => $this->seccion->id_seccion]),
                'data' => "function(d) { d.table = 'docentes-table'; }"
            ])
            ->orderBy(3);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('cedula')->title('Cédula')->width('20%')->orderable(false),
            Column::make('nombre_completo')->title('Nombre y Apellido')->width('30%')->orderable(false),
            Column::make('nivel_asignado')->title('Nivel Académico')->width('15%')->addClass('text-center'),
            Column::make('pnf_base')->title('PNF Base')->width('20%')->orderable(false),
            Column::computed('action')->title('Acciones')->width('15%')->exportable(false)->printable(false)->addClass('text-center'),
        ];
    }
}
