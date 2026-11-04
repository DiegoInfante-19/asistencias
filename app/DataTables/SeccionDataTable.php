<?php

namespace App\DataTables;

use App\Models\Seccion;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class SeccionDataTable extends BaseDataTable
{
    public function dataTable(EloquentBuilder $query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            // RENDERIZADO DE PNFs MÚLTIPLES
            ->addColumn('pnf_nombre', function ($seccion) {
                if ($seccion->pnfs->isEmpty()) {
                    return '<span class="text-muted small fst-italic">Sin PNF asignado</span>';
                }

                $badges = $seccion->pnfs->map(function ($pnf) {
                    return '<span class="badge text-dark bg-dark-subtle border border-dark rounded-3 shadow-sm me-1 mb-1" style="font-size: 0.8rem; font-weight: 500; white-space: normal;">' . e($pnf->nombre_pnf) . '</span>';
                })->implode('');

                return '<div class="d-flex flex-wrap gap-1 align-items-center">' . $badges . '</div>';
            })
            // BADGES DE DOCENTES CON TONO BLANCO GRISÁCEO SUAVE (bg-light-subtle + border-secondary-subtle)
            ->addColumn('profesores_lista', function ($seccion) {
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
            ->filterColumn('profesores_lista', function($query, $keyword) {
                $query->whereHas('profesores.user', function($q) use ($keyword) {
                    $q->where('cedula_users', 'like', "%{$keyword}%")
                      ->orWhereRaw("CONCAT(name_users, ' ', last_name_users) like ?", ["%{$keyword}%"]);
                });
            })
            ->addColumn('action', function ($seccion) {
                return view('estructura_academica.partials.actions_secciones', compact('seccion'))->render();
            })
            ->rawColumns(['pnf_nombre', 'profesores_lista', 'action'])
            ->setRowId('id_seccion');
    }

    public function query(Seccion $model): EloquentBuilder
    {
        $query = $model->newQuery()
            ->select('secciones.*') 
            ->where('id_periodo', $this->id_periodo)
            ->with([
                'pnfs',
                'profesores.user'
            ])
            ->withCount([
                'profesores as n_profesores'
            ]);

        if ($this->request()->filled('filtro_profesor')) {
            $profesorId = $this->request()->get('filtro_profesor');
            $query->whereHas('profesores', function ($q) use ($profesorId) {
                $q->where('profesor_seccion.id_profesor', $profesorId);
            });
        }

        if ($this->request()->filled('filtro_pnf')) {
            $query->porPnf($this->request()->get('filtro_pnf'));
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
                'filtro_profesor' => '$("#filtro_profesor").val()',
                'filtro_pnf' => '$("#filtro_pnf").val()'
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(50)->addClass('text-center align-middle'),
            Column::make('nombre_seccion')->title('Sección')->searchable(true)->addClass('align-middle fw-bold')->width('25%'),
            Column::make('pnf_nombre')->title('PNFs')->searchable(true)->addClass('align-middle')->width('25%'),
            Column::make('profesores_lista')->title('Docentes')->searchable(true)->addClass('align-middle')->width('25%'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width('25%')->addClass('text-center align-middle'),
        ];
    }
}