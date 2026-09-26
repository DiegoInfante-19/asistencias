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
            ->addColumn('pnf_nombre', function ($seccion) {
                return $seccion->pnf->nombre_pnf ?? 'Sin PNF';
            })
            ->addColumn('profesores_lista', function ($seccion) {
                // Usamos el conteo que viene directo de la base de datos
                $cantidad = $seccion->n_profesores ?? $seccion->profesores->count();
                
                if ($cantidad === 0) {
                    return '<span class="text-muted small fst-italic">Sin asignar</span>';
                }

                if ($cantidad <= 4) {
                    return $seccion->profesores->map(function ($profesor) {
                        $nombre = trim(($profesor->user->name_users ?? '') . ' ' . ($profesor->user->last_name_users ?? ''));
                        return '<span class="badge bg-light text-dark border shadow-sm mb-1">' . e($nombre) . '</span>';
                    })->implode('<br>'); // <--- AQUÍ: Salto de línea para apilar verticalmente
                }

                return '<span class="text-primary fw-bold">' . $cantidad . ' Profesores</span>';
            })
            ->addColumn('n_estudiantes', function ($seccion) {
                // Usamos el conteo de la DB para que el ordenamiento funcione
                $cantidad = $seccion->n_estudiantes ?? $seccion->inscripciones->count();
                return '<span class="text-primary fw-bold fs-6"> ' . $cantidad . '</span>';
            })
            ->addColumn('action', function ($seccion) {
                return view('estructura_academica.partials.actions_secciones', compact('seccion'))->render();
            })
            ->rawColumns(['profesores_lista', 'n_estudiantes', 'action'])
            ->setRowId('id_seccion');
    }

    public function query(Seccion $model): EloquentBuilder
    {
        $query = $model->newQuery()
            ->select('secciones.*') // Importante seleccionar la tabla base al usar withCount
            ->where('id_periodo', $this->id_periodo)
            ->with([
                'pnf',
                'profesores.user',
                'inscripciones'
            ])
            // Esto permite que el ordenamiento en las cabeceras funcione sin crashear
            ->withCount([
                'inscripciones as n_estudiantes',
                'profesores as n_profesores'
            ]);

        if ($this->request()->filled('filtro_profesor')) {
            $profesorId = $this->request()->get('filtro_profesor');
            $query->whereHas('profesores', function ($q) use ($profesorId) {
                $q->where('profesor_seccion.id_profesor', $profesorId);
            });
        }

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
                'filtro_profesor' => '$("#filtro_profesor").val()',
                'filtro_pnf' => '$("#filtro_pnf").val()'
            ]);
    }

    protected function getColumns(): array
    {
        // Se removió 'orderable(false)' de todas las columnas de datos
        // Se añadió el método name() para indicarle a Yajra cómo ordenar en la DB
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false)->width(50)->addClass('text-center align-middle'),
            Column::make('nombre_seccion')->title('Sección')->addClass('align-middle fw-bold')->width('20%'),
            Column::make('pnf_nombre')->name('pnf.nombre_pnf')->title('PNF')->addClass('align-middle')->width('20%'),
            Column::make('profesores_lista')->name('n_profesores')->title('Docentes')->addClass('align-middle')->width('20%'),
            Column::make('n_estudiantes')->name('n_estudiantes')->title('Estudiantes')->addClass('align-middle text-center')->width('15%'),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width('20%')->addClass('text-center align-middle'),
        ];
    }
}