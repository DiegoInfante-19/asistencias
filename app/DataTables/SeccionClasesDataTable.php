<?php

namespace App\DataTables;

use App\Models\Seccion;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\Auth;

class SeccionClasesDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            // RENDERIZADO DE PNFs MÚLTIPLES (Estilo idéntico a SeccionDataTable)
            ->addColumn('pnf_nombre', function ($seccion) {
                if ($seccion->pnfs->isEmpty()) {
                    return '<span class="text-muted small fst-italic">Sin PNF asignado</span>';
                }
                
                $badges = $seccion->pnfs->map(function ($pnf) {
                    return '<span class="badge text-dark bg-dark-subtle border border-dark rounded-3 shadow-sm me-1 mb-1" style="font-size: 0.8rem; font-weight: 500; white-space: normal;">' . e($pnf->nombre_pnf) . '</span>';
                })->implode('');

                return '<div class="d-flex flex-wrap gap-1 align-items-center">' . $badges . '</div>';
            })
            // RENDERIZADO DE DOCENTES (Estilo bg-light-subtle + border-secondary-subtle idéntico a SeccionDataTable)
            ->addColumn('profesores_nombres', function ($seccion) {
                $cantidad = $seccion->n_profesores ?? $seccion->profesores->count();

                if ($cantidad === 0) {
                    return '<span class="text-muted small fst-italic">Sin asignar</span>';
                }

                if ($cantidad <= 4) {
                    $badges = $seccion->profesores->map(function ($profesor) {
                        $nombre = trim(($profesor->user->name_users ?? '') . ' ' . ($profesor->user->last_name_users ?? ''));
                        return '<span class="badge bg-light-subtle text-dark border border-secondary-subtle shadow-sm rounded-3 px-2 py-1 me-1 mb-1" style="font-size: 0.78rem; font-weight: 500;">' . e($nombre) . '</span>';
                    })->implode('');

                    return '<div class="d-flex flex-wrap gap-1 align-items-center">' . $badges . '</div>';
                }

                return '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-bold">' . $cantidad . ' Profesores</span>';
            })
            ->filterColumn('pnf_nombre', function($query, $keyword) {
                $query->whereHas('pnfs', function($q) use ($keyword) {
                    $q->where('nombre_pnf', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('profesores_nombres', function($query, $keyword) {
                $query->whereHas('profesores.user', function($q) use ($keyword) {
                    $q->where('cedula_users', 'like', "%{$keyword}%")
                      ->orWhereRaw("CONCAT(name_users, ' ', last_name_users) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('cohorte_num', function ($seccion) {
                return $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'S/C';
            })
            ->addColumn('clases_vistas', function ($seccion) {
                $vistas = $seccion->clases_vistas ?? 0;
                return $vistas > 0
                    ? '<span class="fw-bold text-primary fs-6">' . $vistas . '</span>'
                    : '<span class="text-muted small fst-italic">Sin clases</span>';
            })
            ->addColumn('clases_programadas', function ($seccion) {
                $prog = $seccion->clases_programadas ?? 0;
                return $prog > 0
                    ? '<span class="fw-bold text-primary fs-6">' . $prog . '</span>'
                    : '<span class="text-muted small fst-italic">Sin clases</span>';
            })
            ->addColumn('clases_totales', function ($seccion) {
                $total = $seccion->clases_totales ?? 0;
                return $total > 0
                    ? '<span class="fw-bold text-primary fs-6">' . $total . '</span>'
                    : '<span class="text-muted small fst-italic">Sin clases</span>';
            })
            ->addColumn('action', function ($seccion) {
                return view('sesiones.partials.actions_secciones', compact('seccion'))->render();
            })
            ->rawColumns(['pnf_nombre', 'profesores_nombres', 'clases_vistas', 'clases_programadas', 'clases_totales', 'action'])
            ->setRowId('id_seccion');
    }

    public function query(Seccion $model): EloquentBuilder
    {
        $query = $model->newQuery()
            ->select('secciones.*')
            ->with(['pnfs', 'periodoAcademico.cohorte', 'profesores.user'])
            ->withCount([
                'profesores as n_profesores',
                'sesiones as clases_totales',
                'sesiones as clases_vistas' => function ($q) {
                    $q->has('asistencias');
                },
                'sesiones as clases_programadas' => function ($q) {
                    $q->doesntHave('asistencias');
                }
            ]);

        $query->activasParaAsignacion();

        $user = Auth::user();
        $request = $this->request();

        if (!$user->isAdmin() && !$user->isCoordinador()) {
            if ($user->profesor) {
                $query->porProfesor($user->profesor->id_profesor);
            } else {
                $query->whereRaw('1 = 0');
            }
        } else {
            if ($request->filled('id_profesor')) {
                $query->porProfesor($request->get('id_profesor'));
            }
        }

        if ($request->filled('id_pnf')) {
            $query->porPnf($request->get('id_pnf'));
        }

        return $query;
    }

    protected function getTableId(): string
    {
        return 'secciones-clases-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->ajax([
                'url' => route('clases.secciones.index'),
                'data' => 'function(d) {
                    d.id_pnf = $("#filtro_pnf").val();
                    d.id_profesor = $("#filtro_profesor").length ? $("#filtro_profesor").val() : null;
                }'
            ])
            ->buttons([
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel')->addClass('btn btn-success btn-sm shadow-sm'),
                Button::make('pdf')->text('<i class="bi bi-file-earmark-pdf"></i> PDF')->addClass('btn btn-danger btn-sm shadow-sm'),
                Button::make('print')->text('<i class="bi bi-printer"></i> Imprimir')->addClass('btn btn-secondary btn-sm shadow-sm'),
                Button::make('colvis')->text('<i class="bi bi-layout-three-columns me-1"></i> Columnas')->addClass('btn btn-outline-secondary btn-sm shadow-sm')
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->addClass('text-center align-middle'),
            Column::make('nombre_seccion')->title('Sección')->width(140)->addClass('align-middle fw-bold'),
            Column::make('pnf_nombre')->title('PNFs')->searchable(true)->addClass('align-middle'),
            Column::make('profesores_nombres')->name('n_profesores')->title('Profesor/es')->searchable(true)->addClass('align-middle'),
            Column::make('clases_vistas')->name('clases_vistas')->title('N° de clases vistas')->searchable(false)->addClass('text-center align-middle'),
            Column::make('clases_programadas')->name('clases_programadas')->title('N° de clases pendientes')->searchable(false)->addClass('text-center align-middle'),
            Column::make('clases_totales')->name('clases_totales')->title('N° total clases')->searchable(false)->addClass('text-center align-middle'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->addClass('text-center align-middle'),
        ];
    }
}