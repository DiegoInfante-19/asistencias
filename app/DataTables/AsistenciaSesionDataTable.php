<?php

namespace App\DataTables;

use App\Models\InscripcionSeccion;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class AsistenciaSesionDataTable extends BaseDataTable
{
    protected $idSesion;
    protected $idSeccion;
    protected $idPnfSeccion;
    protected $asistenciasRegistradas = [];
    protected $puedeEditar = true;

    public function withSesionData(int $idSesion, int $idSeccion, int $idPnfSeccion, array $asistenciasRegistradas, bool $puedeEditar): self
    {
        $this->idSesion = $idSesion;
        $this->idSeccion = $idSeccion;
        $this->idPnfSeccion = $idPnfSeccion;
        $this->asistenciasRegistradas = $asistenciasRegistradas;
        $this->puedeEditar = $puedeEditar;
        return $this;
    }

    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('cedula_personas', function ($inscripcion) {
                return '<span class="text-dark">V-' . e($inscripcion->persona->cedula_personas ?? 'S/C') . '</span>';
            })
            ->addColumn('estudiante_nombre', function ($inscripcion) {
                return '<span class="fw-medium text-dark">' . e($inscripcion->persona->nombre_completo ?? 'N/D') . '</span>';
            })
            ->addColumn('titulo_optando', function ($inscripcion) {
                $titulacionPersona = $inscripcion->persona->titulacionPersona;

                if ($titulacionPersona && $titulacionPersona->id_titulacion && $titulacionPersona->id_pnf == $this->idPnfSeccion) {
                    $tituloEspecifico = DB::table('titulos_pnf')
                        ->where('id_pnf', $titulacionPersona->id_pnf)
                        ->where('id_titulo', $titulacionPersona->id_titulacion)
                        ->value('nombre_titulo_pnf');

                    $nombreMostrar = $tituloEspecifico ?? $titulacionPersona->titulacion->nombre_titulo_base ?? 'Título Desconocido';
                    return '<span class="text-secondary">' . e($nombreMostrar) . '</span>';
                }

                return '<span class="text-muted fst-italic">Sin titulación asignada para este PNF</span>';
            })
            ->addColumn('cohorte_origen', function ($inscripcion) {
                return '<span class="text-dark">' . e($inscripcion->persona->cohorte->numero_cohorte ?? 'N/D') . '</span>';
            })
            ->addColumn('estado_asistencia', function ($inscripcion) {
                $estadoActual = $this->asistenciasRegistradas[$inscripcion->id_inscripcion_seccion] ?? \App\Enums\EstadoAsistencia::PRESENTE;
                $puedeEditar = $this->puedeEditar;
                // Calculamos si ya existen asistencias registradas
                $tieneAsistencia = count($this->asistenciasRegistradas) > 0;

                return view('sesiones.partials.estado_asistencia', compact('inscripcion', 'estadoActual', 'puedeEditar', 'tieneAsistencia'))->render();
            })
            ->rawColumns(['cedula_personas', 'estudiante_nombre', 'titulo_optando', 'cohorte_origen', 'estado_asistencia'])
            ->setRowId(function ($inscripcion) {
                return 'fila_' . $inscripcion->id_inscripcion_seccion;
            });
    }

    public function query(InscripcionSeccion $model): EloquentBuilder
    {
        return $model->newQuery()
            ->with(['persona.cohorte', 'persona.titulacionPersona.titulacion'])
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
            ->orderBy(2, 'asc')
            ->parameters([
                'responsive' => false,
                'autoWidth' => false,
                'scrollX' => true,
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->addClass('text-center align-middle')->width(40),
            Column::make('cedula_personas')->title('Cédula')->addClass('align-middle')->width(110),
            Column::make('estudiante_nombre')->title('Apellidos y Nombres')->searchable(false)->orderable(false)->addClass('align-middle'),
            Column::make('titulo_optando')->title('Título a Optar')->searchable(false)->orderable(false)->addClass('align-middle'),
            Column::make('cohorte_origen')->title('Cohorte')->searchable(false)->orderable(false)->addClass('text-center align-middle')->width(100),
            Column::computed('estado_asistencia')->title('Acciones')->exportable(false)->printable(false)->addClass('text-center align-middle')->width(280),
        ];
    }
}