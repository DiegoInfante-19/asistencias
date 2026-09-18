<?php

namespace App\DataTables;

use App\Models\Seccion;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button; // <-- Importación necesaria para los botones
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\Auth;

class SeccionClasesDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('pnf_nombre', function ($seccion) {
                return $seccion->pnf->nombre_pnf ?? 'Sin PNF';
            })
            ->addColumn('profesores_nombres', function ($seccion) {
                if ($seccion->profesores->isEmpty()) {
                    return '<span class="text-muted small fst-italic">Sin asignar</span>';
                }
                return $seccion->profesores->map(function ($profesor) {
                    $nombre = trim(($profesor->user->name_users ?? '') . ' ' . ($profesor->user->last_name_users ?? ''));
                    return '• ' . $nombre;
                })->implode('<br>');
            })
            ->addColumn('cohorte_num', function ($seccion) {
                return $seccion->periodoAcademico->cohorte->numero_cohorte ?? 'S/C';
            })
            // 1. Columna: Clases Vistas (Ya se pasó asistencia)
            ->addColumn('clases_vistas', function ($seccion) {
                $vistas = $seccion->clases_vistas ?? 0;
                return $vistas > 0 
                    ? '<span class="fw-bold text-primary fs-6">'.$vistas.'</span>' 
                    : '<span class="text-muted small fst-italic">Sin clases</span>';
            })
            // 2. Columna: Clases Programadas (Pendientes de pasar asistencia)
            ->addColumn('clases_programadas', function ($seccion) {
                $prog = $seccion->clases_programadas ?? 0;
                return $prog > 0 
                    ? '<span class="fw-bold text-primary fs-6">'.$prog.'</span>' 
                    : '<span class="text-muted small fst-italic">Sin clases</span>';
            })
            // 3. Columna: Total de Clases registradas
            ->addColumn('clases_totales', function ($seccion) {
                $total = $seccion->clases_totales ?? 0;
                return $total > 0 
                    ? '<span class="fw-bold text-primary fs-6">'.$total.'</span>' 
                    : '<span class="text-muted small fst-italic">Sin clases</span>';
            })
            ->addColumn('action', function ($seccion) {
                return view('sesiones.partials.actions_secciones', compact('seccion'))->render();
            })
            // Habilitar la interpretación de HTML para las nuevas columnas
            ->rawColumns(['profesores_nombres', 'clases_vistas', 'clases_programadas', 'clases_totales', 'action'])
            ->setRowId('id_seccion');
    }

    public function query(Seccion $model): EloquentBuilder
    {
        $query = $model->newQuery()
            ->select('secciones.*')
            ->with(['pnf', 'periodoAcademico.cohorte', 'profesores.user'])
            // Aquí optimizamos la consulta para que devuelva los 3 conteos a la vez
            ->withCount([
                'sesiones as clases_totales',
                'sesiones as clases_vistas' => function ($q) {
                    $q->has('asistencias'); // Tienen asistencia
                },
                'sesiones as clases_programadas' => function ($q) {
                    $q->doesntHave('asistencias'); // No tienen asistencia
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

        if ($request->filled('id_empresa')) {
            $query->porEmpresa($request->get('id_empresa'));
        }

        if ($request->filled('id_titulo')) {
            $query->porTitulo($request->get('id_titulo'));
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
                    d.id_empresa = $("#filtro_empresa").val();
                    d.id_titulo = $("#filtro_titulo").val();
                }'
            ])
            // Al quitar el "dom()" manual, DataTables recupera tu Buscador, Paginación y Contador originales.
            // Y aquí configuramos la lista de botones completa, agregando los clásicos más el de columnas:
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
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->addClass('text-center'),
            Column::make('nombre_seccion')->title('Sección')->width(140),
            Column::make('pnf_nombre')->title('PNF')->searchable(false),
            Column::make('profesores_nombres')->title('Profesor')->searchable(false),
            Column::make('clases_vistas')->title('Vistas')->searchable(false)->orderable(true)->addClass('text-center align-middle'),
            Column::make('clases_programadas')->title('Pendientes')->searchable(false)->orderable(true)->addClass('text-center align-middle'),
            Column::make('clases_totales')->title('Total Clases')->searchable(false)->orderable(true)->addClass('text-center align-middle'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->addClass('text-center'),
        ];
    }
}