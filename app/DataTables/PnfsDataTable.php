<?php

namespace App\DataTables;

use App\Models\Pnf;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class PnfsDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addIndexColumn()
            ->addColumn('total_estudiantes', function ($pnf) {
                $query = \App\Models\InscripcionSeccion::where('estatus_inscripcion', 'Activo')
                    ->whereHas('seccion.pnfs', function ($q) use ($pnf) {
                        $q->where('pnfs.id_pnf', $pnf->id_pnf);
                    });

                if (request()->filled('seccion')) {
                    $query->where('id_seccion', request()->seccion);
                }
                if (request()->filled('empresa')) {
                    $query->whereHas('persona.empresaPersona', fn($q) => $q->where('id_empresa', request()->empresa));
                }

                return '<span class="fw-bold text-dark">' . $query->count() . '</span>';
            })
            ->addColumn('total_empresas', function ($pnf) {
                $query = \App\Models\InscripcionSeccion::where('estatus_inscripcion', 'Activo')
                    ->whereHas('seccion.pnfs', function ($q) use ($pnf) {
                        $q->where('pnfs.id_pnf', $pnf->id_pnf);
                    });

                if (request()->filled('seccion')) {
                    $query->where('id_seccion', request()->seccion);
                }
                if (request()->filled('empresa')) {
                    $query->whereHas('persona.empresaPersona', fn($q) => $q->where('id_empresa', request()->empresa));
                }

                $empresas = $query->with('persona.empresaPersona')->get()
                    ->pluck('persona.empresaPersona.id_empresa')
                    ->filter()->unique()->count();

                return '<span class="fw-bold text-dark">' . $empresas . '</span>';
            })
            ->editColumn('vigencia_pnf', function ($pnf) {
                if ($pnf->vigencia_pnf) {
                    // Estado Activo: Fondo verde claro con borde y texto verde oscuro de alto contraste
                    return '<span class="badge px-3 py-2 shadow-sm" style="background-color: #d1e7dd; color: #0f5132; border: 1px solid #198754; font-weight: 500; font-size: 0.85rem;">Activo</span>';
                }
                // Estado Inactivo: Fondo rojo claro con borde y texto rojo oscuro de alto contraste
                return '<span class="badge px-3 py-2 shadow-sm" style="background-color: #fce8e6; color: #b02a37; border: 1px solid #b02a37; font-weight: 500; font-size: 0.85rem;">Inactivo</span>';
            })
            ->addColumn('action', function ($pnf) {
                return view('pnfs.partials.actions', compact('pnf'))->render();
            })
            ->rawColumns(['total_estudiantes', 'total_empresas', 'vigencia_pnf', 'action'])
            ->setRowId('id_pnf');
    }

    public function query(Pnf $model): EloquentBuilder
    {
        $query = $model->newQuery()->select([
            'id_pnf',
            'nombre_pnf',
            'descripcion_pnf',
            'vigencia_pnf'
        ]);

        if (request()->filled('pnf')) {
            $query->where('id_pnf', request()->pnf);
        }

        if (request()->filled('seccion') || request()->filled('empresa')) {
            $query->whereHas('secciones.inscripciones', function ($q) {
                $q->where('estatus_inscripcion', 'Activo');
                if (request()->filled('seccion')) {
                    $q->where('id_seccion', request()->seccion);
                }
                if (request()->filled('empresa')) {
                    $q->whereHas('persona.empresaPersona', fn($sub) => $sub->where('id_empresa', request()->empresa));
                }
            });
        }

        return $query;
    }

    protected function getTableId(): string
    {
        return 'pnfs-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(40)->addClass('text-center'),
            Column::make('nombre_pnf')->title('Programa Nacional de Formación (PNF)'),
            Column::computed('total_estudiantes')->title('Estudiantes Activos')->addClass('text-center'),
            Column::computed('total_empresas')->title('Empresas Aliadas')->addClass('text-center'),
            Column::make('vigencia_pnf')->title('Estado')->addClass('text-center'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(140)->addClass('text-center'),
        ];
    }
}