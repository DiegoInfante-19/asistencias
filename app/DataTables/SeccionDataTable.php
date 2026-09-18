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
                $cantidad = $seccion->profesores->count();
                if ($cantidad === 0) {
                    return '<span class="text-muted small">Sin asignar</span>';
                }
                return '<span class="badge bg-info text-dark">' . $cantidad . ' Docente(s)</span>';
            })
            ->addColumn('n_estudiantes', function ($seccion) {
                $cantidad = $seccion->inscripciones->count();
                return '<span class="badge bg-light text-dark border"><i class="bi bi-people-fill text-primary me-1"></i> ' . $cantidad . '</span>';
            })
            ->addColumn('action', function ($seccion) {
                // Botón directo para ir al "Nivel 3" (secciones.show)
                $urlEntrar = route('secciones.show', $seccion->id_seccion);
                $btnEntrar = '<a href="'.$urlEntrar.'" class="btn btn-sm btn-outline-primary shadow-sm me-1" title="Gestionar Estudiantes"><i class="bi bi-eye"></i></a>';
                
                // AQUÍ ESTÁ EL CAMBIO CLAVE: Apuntamos al nuevo archivo partials
                $accionesExtra = view('estructura_academica.partials.actions_secciones', compact('seccion'))->render();
                
                return '<div class="d-flex justify-content-center align-items-center">' . $btnEntrar . $accionesExtra . '</div>';
            })
            ->rawColumns(['profesores_lista', 'n_estudiantes', 'action'])
            ->setRowId('id_seccion');
    }

    public function query(Seccion $model): EloquentBuilder
    {
        // 1. RESTRICCIÓN OBLIGATORIA: Filtramos estrictamente por el id_periodo inyectado en el Controlador
        $query = $model->newQuery()
            ->where('id_periodo', $this->id_periodo)
            ->with([
                'pnf',
                'profesores.user',
                'inscripciones'
            ]);

        // 2. FILTRO AVANZADO: Por Profesor
        if ($this->request()->filled('filtro_profesor')) {
            $profesorId = $this->request()->get('filtro_profesor');
            $query->whereHas('profesores', function ($q) use ($profesorId) {
                $q->where('profesor_seccion.id_profesor', $profesorId);
            });
        }

        // 3. FILTRO AVANZADO: Por PNF 
        // Nota: en la Fase 3 aseguraremos que el select del frontend envíe el ID del PNF
        if ($this->request()->filled('filtro_pnf')) {
            $pnfId = $this->request()->get('filtro_pnf');
            $query->where('id_pnf', $pnfId);
        }

        return $query;
    }

    protected function getTableId(): string
    {
        return 'secciones-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
                    ->minifiedAjax('', null, [
                        // Capturamos los filtros de los Select2 que haremos en la Fase 3
                        'filtro_profesor' => '$("#filtro_profesor").val()',
                        'filtro_pnf' => '$("#filtro_pnf").val()'
                    ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('nombre_seccion')->title('Sección')->addClass('text-center fw-bold')->width('25%'),
            Column::make('pnf.nombre_pnf')->title('PNF')->addClass('text-center')->width('20%')->orderable(false),
            Column::make('profesores_lista')->title('Docentes')->addClass('text-center')->orderable(false)->width('15%'),
            Column::make('n_estudiantes')->title('Estudiantes')->addClass('text-center')->orderable(false)->width('15%'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width('25%')->addClass('text-center'),
        ];
    }
}