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
            ->addColumn('profesores_lista', function ($seccion) {
                // CONTADOR DE DOCENTES EN VEZ DE NOMBRES
                $cantidad = $seccion->profesores->count();
                if ($cantidad === 0) {
                    return '<span class="text-muted small">Sin asignar</span>';
                }
                return '<span class="badge bg-info text-dark">' . $cantidad . ' Asignado(s)</span>';
            })
            ->addColumn('action', function ($seccion) {
                return view('secciones.partials.actions', compact('seccion'))->render();
            })
            ->rawColumns(['profesores_lista', 'action'])
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
                        'filtro_profesor' => '$("#filtro_profesor").val()',
                        'filtro_empresa' => '$("#filtro_empresa").val()',
                        'filtro_cohorte' => '$("#filtro_cohorte").val()'
                    ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('nombre_seccion')->title('Sección')->addClass('text-center fw-bold')->width('40%'),
            Column::make('profesores_lista')->title('Docentes Asignados')->addClass('text-center')->orderable(false)->width('35%'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width('25%')->addClass('text-center'),
        ];
    }
}