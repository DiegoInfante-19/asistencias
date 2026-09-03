<?php

namespace App\DataTables;

use App\Models\Seccion;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class SeccionDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addColumn('periodo', function ($seccion) {
                return 'Cohorte ' . ($seccion->periodoAcademico->cohorte->numero_cohorte ?? 'N/D');
            })
            ->addColumn('pnf_nombre', function ($seccion) {
                return '<span class="fw-bold text-primary">' . ($seccion->pnf->nombre_pnf ?? 'N/D') . '</span>';
            })
            ->addColumn('profesores_lista', function ($seccion) {
                if ($seccion->profesores->isEmpty()) {
                    return '<span class="text-muted small">Sin asignar</span>';
                }
                return $seccion->profesores->map(function ($profesor) {
                    $nombre = $profesor->user->name_users ?? 'Profesor';
                    $apellido = $profesor->user->last_name_users ?? '';
                    return '<span class="badge bg-info text-dark me-1">' . trim("$nombre $apellido") . '</span>';
                })->implode('');
            })
            ->addColumn('total_estudiantes', function ($seccion) {
                $count = $seccion->inscripciones()->where('estatus_inscripcion', 'Activo')->count();
                return '<span class="badge bg-secondary px-2 py-1">' . $count . ' Estudiantes</span>';
            })
            ->editColumn('estatus_seccion', function ($seccion) {
                if ($seccion->estatus_seccion === 'Activa') {
                    return '<span class="badge bg-success">Activa</span>';
                }
                return '<span class="badge bg-secondary">Inactiva</span>';
            })
            ->addColumn('action', function ($seccion) {
                return view('secciones.partials.actions', compact('seccion'))->render();
            })
            ->rawColumns(['pnf_nombre', 'profesores_lista', 'total_estudiantes', 'estatus_seccion', 'action'])
            ->setRowId('id_seccion');
    }

    public function query(Seccion $model): EloquentBuilder
    {
        $query = $model->newQuery()->with([
            'periodoAcademico.cohorte',
            'pnf',
            'profesores.user',
            'inscripciones.persona.empresaPersona'
        ]);

        // FILTRO AVANZADO: Por PNF
        if ($this->request()->filled('filtro_pnf')) {
            $query->where('id_pnf', $this->request()->get('filtro_pnf'));
        }

        // FILTRO AVANZADO: Por Profesor (Relación N:M)
        if ($this->request()->filled('filtro_profesor')) {
            $profesorId = $this->request()->get('filtro_profesor');
            $query->whereHas('profesores', function ($q) use ($profesorId) {
                $q->where('profesor_seccion.id_profesor', $profesorId);
            });
        }

        // FILTRO AVANZADO: Por Empresa (Si al menos un estudiante de la sección trabaja allí)
        if ($this->request()->filled('filtro_empresa')) {
            $empresaId = $this->request()->get('filtro_empresa');
            $query->whereHas('inscripciones.persona.empresaPersona', function ($q) use ($empresaId) {
                $q->where('id_empresa', $empresaId);
            });
        }

        // FILTRO AVANZADO: Por Cohorte (Si al menos un estudiante de la sección pertenece a esta cohorte estática)
        if ($this->request()->filled('filtro_cohorte')) {
            $cohorteId = $this->request()->get('filtro_cohorte');
            $query->whereHas('inscripciones.persona', function ($q) use ($cohorteId) {
                $q->where('id_cohortes', $cohorteId);
            });
        }

        return $query;
    }

    protected function getTableId(): string
    {
        return 'secciones-table';
    }

    public function html(): HtmlBuilder
    {
        // Llamamos al constructor base que ya tiene los botones, DOM y el idioma local
        return $this->sharedHtmlBuilder()
                    ->minifiedAjax('', null, [
                        'filtro_pnf' => '$("#filtro_pnf").val()',
                        'filtro_profesor' => '$("#filtro_profesor").val()',
                        'filtro_empresa' => '$("#filtro_empresa").val()',
                        'filtro_cohorte' => '$("#filtro_cohorte").val()'
                    ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id_seccion')->title('ID')->width('50px')->addClass('text-center'),
            Column::make('nombre_seccion')->title('Sección')->addClass('text-center fw-bold'),
            Column::make('periodo')->title('Período Base'),
            Column::make('pnf_nombre')->title('PNF'),
            Column::make('profesores_lista')->title('Docentes Asignados')->orderable(false),
            Column::make('total_estudiantes')->title('Inscritos')->addClass('text-center')->orderable(false),
            Column::make('estatus_seccion')->title('Estatus')->addClass('text-center'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width('130px')->addClass('text-center'),
        ];
    }
}