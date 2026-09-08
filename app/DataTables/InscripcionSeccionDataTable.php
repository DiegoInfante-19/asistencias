<?php

namespace App\DataTables;

use App\Models\InscripcionSeccion;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class InscripcionSeccionDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('cedula', function ($inscripcion) {
                return '<span class="fw-bold">V-' . ltrim($inscripcion->persona->cedula_personas ?? '', 'V-') . '</span>';
            })
            ->addColumn('nombre_corto', function ($inscripcion) {
                return $inscripcion->persona->nombre_corto ?? 'N/D';
            })
            ->addColumn('cohorte', function ($inscripcion) {
                $num = $inscripcion->persona->cohorte->numero_cohorte ?? 'Externa';
                // Cambiado a texto grueso y color azul primario
                return '<span class="fw-bold text-primary">' . $num . '</span>';
            })
            ->addColumn('titulo_optar', function ($inscripcion) {
                return $inscripcion->persona->titulo_base ?? 'N/D';
            })
            ->addColumn('action', function ($inscripcion) {
                $seccionId = $this->seccion->id_seccion;
                $inscripcionId = $inscripcion->id_inscripcion_seccion;
                $url = route('secciones.retirar', [$seccionId, $inscripcionId]);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return <<<HTML
                <form action="{$url}" method="POST" class="d-inline" onsubmit="return confirm('¿Retirar a este estudiante de la sección?');">
                    {$csrf}
                    {$method}
                    <button type="submit" class="btn btn-danger btn-sm">Retirar</button>
                </form>
HTML;
            })
            ->rawColumns(['cedula', 'cohorte', 'action'])
            ->setRowId('id_inscripcion_seccion');
    }

    public function query(InscripcionSeccion $model): EloquentBuilder
    {
        return $model->newQuery()
            ->with([
                'persona.cohorte',
                'persona.titulacionPersona.titulacion',
                'persona.titulacionPersona.pnf'
            ])
            ->where('id_seccion', $this->seccion->id_seccion);
    }

    protected function getTableId(): string
    {
        return 'inscripciones-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->ajax(route('secciones.show', ['seccion' => $this->seccion->id_seccion, 'table' => 'estudiantes-table']));
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('cedula')->title('Cédula')->width('15%'),
            Column::make('nombre_corto')->title('Nombres y Apellidos')->width('35%'),
            Column::make('cohorte')->title('Cohorte')->width('15%')->addClass('text-center'),
            Column::make('titulo_optar')->title('Título a Optar')->width('20%'),
            Column::computed('action')->title('Acciones')->width('15%')->exportable(false)->printable(false)->addClass('text-center'),
        ];
    }
}
