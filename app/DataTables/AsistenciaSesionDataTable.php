<?php

namespace App\DataTables;

use App\Models\InscripcionSeccion;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class AsistenciaSesionDataTable extends BaseDataTable
{
    protected $idSesion;
    protected $idSeccion;
    protected $asistenciasRegistradas = [];
    protected $puedeEditar = true;

    public function withSesionData(int $idSesion, int $idSeccion, array $asistenciasRegistradas, bool $puedeEditar): self
    {
        $this->idSesion = $idSesion;
        $this->idSeccion = $idSeccion;
        $this->asistenciasRegistradas = $asistenciasRegistradas;
        $this->puedeEditar = $puedeEditar;
        return $this;
    }

    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('cedula_personas', function ($inscripcion) {
                return '<span class="fw-semibold text-secondary">V-' . e($inscripcion->persona->cedula_personas ?? 'S/C') . '</span>';
            })
            ->addColumn('estudiante_nombre', function ($inscripcion) {
                return '<span class="fw-bold text-dark">' . e($inscripcion->persona->nombre_completo ?? 'N/D') . '</span>';
            })
            ->addColumn('cohorte_origen', function ($inscripcion) {
                return '<span class="badge bg-info text-dark">Cohorte ' . e($inscripcion->persona->cohorte->numero_cohorte ?? 'N/D') . '</span>';
            })
            ->addColumn('estado_asistencia', function ($inscripcion) {
                $estadoActual = $this->asistenciasRegistradas[$inscripcion->id_inscripcion_seccion] ?? 'Presente';
                $puedeEditar = $this->puedeEditar;

                return view('sesiones.partials.estado_asistencia', compact('inscripcion', 'estadoActual', 'puedeEditar'))->render();
            })
            ->rawColumns(['cedula_personas', 'estudiante_nombre', 'cohorte_origen', 'estado_asistencia'])
            ->setRowId('id_inscripcion_seccion');
    }

    public function query(InscripcionSeccion $model): EloquentBuilder
    {
        return $model->newQuery()
            ->with(['persona.cohorte'])
            ->where('id_seccion', $this->idSeccion)
            ->where('estatus_inscripcion', 'Activo')
            ->select('inscripciones_secciones.*');
    }

    protected function getTableId(): string
    {
        return 'asistencia-sesion-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->ajax([
                'url' => route('sesiones.show', $this->idSesion),
            ])
            ->orderBy(2, 'asc');
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('cedula_personas')->title('Cédula')->width(120),
            Column::make('estudiante_nombre')->title('Apellidos y Nombres')->searchable(false)->orderable(false),
            Column::make('cohorte_origen')->title('Cohorte (Origen)')->searchable(false)->orderable(false)->addClass('text-center')->width(140),
            Column::computed('estado_asistencia')->title('Estado de Asistencia')->exportable(false)->printable(false)->width(320)->addClass('text-center'),
        ];
    }
}