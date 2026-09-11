<?php

namespace App\DataTables;

use App\Models\Seccion;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
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
            // Usamos editColumn para el campo sesiones_count proveniente de withCount()
            ->editColumn('sesiones_count', function ($seccion) {
                $total = $seccion->sesiones_count ?? 0;

                if ($total > 0) {
                    return '<span class="fw-bold text-primary fs-6">' . $total . '</span>';
                }
                
                return '<span class="text-muted small fst-italic">Sin clases</span>';
            })
            ->addColumn('action', function ($seccion) {
                return view('sesiones.partials.actions_secciones', compact('seccion'))->render();
            })
            ->rawColumns(['profesores_nombres', 'sesiones_count', 'action'])
            ->setRowId('id_seccion');
    }

    public function query(Seccion $model): EloquentBuilder
    {
        // CORRECCIÓN A: Primero select('secciones.*') y luego withCount('sesiones')
        $query = $model->newQuery()
            ->select('secciones.*')
            ->with(['pnf', 'periodoAcademico.cohorte', 'profesores.user'])
            ->withCount('sesiones');

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
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->addClass('text-center'),
            Column::make('nombre_seccion')->title('Sección')->width(140),
            Column::make('pnf_nombre')->title('PNF')->searchable(false),
            Column::make('profesores_nombres')->title('Profesor')->searchable(false),
            Column::make('sesiones_count')->title('Clases')->searchable(false)->orderable(true)->addClass('text-center'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->addClass('text-center'),
        ];
    }
}