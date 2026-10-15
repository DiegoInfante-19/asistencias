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
            ->addColumn('titulo_optar', function ($inscripcion) {
                return $inscripcion->persona->titulo_base ?? 'N/D';
            })
            ->filterColumn('cedula', function($query, $keyword) {
                $query->where('personas.cedula_personas', 'like', "%{$keyword}%");
            })
            ->filterColumn('nombre_corto', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->whereRaw("CONCAT(personas.primer_nombre_personas, ' ', personas.primer_apellido_personas) like ?", ["%{$keyword}%"])
                      ->orWhereRaw("CONCAT(personas.primer_nombre_personas, ' ', personas.segundo_nombre_personas, ' ', personas.primer_apellido_personas, ' ', personas.segundo_apellido_personas) like ?", ["%{$keyword}%"]);
                });
            })
            ->filterColumn('titulo_optar', function($query, $keyword) {
                $query->whereHas('persona.titulacionPersona.titulacion', function($q) use ($keyword) {
                    $q->where('nombre_titulo_base', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('cedula', function ($query, $order) {
                $query->orderBy('personas.cedula_personas', $order);
            })
            ->orderColumn('nombre_corto', function ($query, $order) {
                $query->orderBy('personas.primer_nombre_personas', $order)
                      ->orderBy('personas.primer_apellido_personas', $order);
            })
            ->addColumn('action', function ($inscripcion) {
                // Si la sección está inactiva, bloqueamos el botón de retiro
                if (!$this->seccion->estaActiva()) {
                    return '<span class="badge bg-secondary">Bloqueado (Inactiva)</span>';
                }

                $seccionId = $this->seccion->id_seccion;
                $inscripcionId = $inscripcion->id_inscripcion_seccion;
                $url = route('secciones.retirar', [$seccionId, $inscripcionId]);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return <<<HTML
        <form action="{$url}" method="POST" class="d-inline" onsubmit="return confirm('¿Retirar a este estudiante de la sección?');">
            {$csrf}
            {$method}
            <button type="submit" class="btn btn-danger shadow-sm">Remover</button>
        </form>
HTML;
            })
            ->rawColumns(['cedula', 'action'])
            ->setRowId('id_inscripcion_seccion');
    }

    public function query(InscripcionSeccion $model): EloquentBuilder
    {
        return $model->newQuery()
            ->select('inscripciones_secciones.*')
            ->join('personas', 'inscripciones_secciones.id_personas', '=', 'personas.id_personas')
            ->with([
                'persona.titulacionPersona.titulacion',
                'persona.titulacionPersona.pnf'
            ])
            ->where('inscripciones_secciones.id_seccion', $this->seccion->id_seccion);
    }

    protected function getTableId(): string
    {
        return 'inscripciones-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->orderBy(1, 'asc')
            ->ajax(route('secciones.show', ['seccion' => $this->seccion->id_seccion, 'table' => 'estudiantes-table']));
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('cedula')->title('Cédula')->searchable(true)->width('15%'),
            Column::make('nombre_corto')->title('Nombres y Apellidos')->searchable(true)->width('25%'),
            Column::make('titulo_optar')->title('Título a Optar')->searchable(true)->width('49%'),
            Column::computed('action')->title('Acciones')->width('20%')->exportable(false)->printable(false)->addClass('text-center'),
        ];
    }
}