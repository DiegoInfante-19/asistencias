<?php

namespace App\DataTables;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class PersonasDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addColumn('full_name', function ($persona) {
                return $persona->primer_nombre_personas . ' ' . $persona->primer_apellido_personas;
            })
            ->addColumn('empresa', function ($persona) {
                return $persona->empresaPersona && $persona->empresaPersona->empresa
                    ? $persona->empresaPersona->empresa->nombre_empresa
                    : '<span class="text-muted fst-italic">Sin empresa</span>';
            })
            ->addColumn('titulo', function ($persona) {
                if ($persona->titulacionPersona && $persona->titulacionPersona->id_titulacion) {
                    $tituloEspecifico = DB::table('titulos_pnf')
                        ->where('id_pnf', $persona->titulacionPersona->id_pnf)
                        ->where('id_titulo', $persona->titulacionPersona->id_titulacion)
                        ->value('nombre_titulo_pnf');

                    $nombreMostrar = $tituloEspecifico ?? $persona->titulacionPersona->titulacion->nombre_titulo_base ?? 'Título Desconocido';
                    
                    return $nombreMostrar;
                }
                
                return '<span class="text-muted fst-italic">No asignado</span>';
            })
            ->addColumn('action', function ($persona) {
                return view('personas.partials.actions', compact('persona'))->render();
            })
            ->setRowClass(function ($persona) {
                if ($persona->sexo_personas === 'M') {
                    return 'bg-masculino';
                } elseif ($persona->sexo_personas === 'F') {
                    return 'bg-femenino';
                }
                return '';
            })
            ->filter(function ($query) {
                // 1. Filtro por Cohorte (Funciona en el backend aunque no se muestre la columna)
                if (request()->has('filtro_cohorte') && !empty(request()->get('filtro_cohorte'))) {
                    $query->where('id_cohortes', request()->get('filtro_cohorte'));
                }

                // 2. Filtro por Empresa
                if (request()->has('filtro_empresa') && !empty(request()->get('filtro_empresa'))) {
                    $query->whereHas('empresaPersona', function ($q) {
                        $q->where('id_empresa', request()->get('filtro_empresa'));
                    });
                }
                
                // 3. Filtro por Cargo
                if (request()->has('filtro_cargo') && !empty(request()->get('filtro_cargo'))) {
                    $query->whereHas('empresaPersona', function ($q) {
                        $q->where('id_cargo', request()->get('filtro_cargo'));
                    });
                }

                // 4. Filtro por PNF
                if (request()->has('filtro_pnf') && !empty(request()->get('filtro_pnf'))) {
                    $query->whereHas('titulacionPersona', function ($q) {
                        $q->where('id_pnf', request()->get('filtro_pnf'));
                    });
                }

                // 5. Filtro por Título a Optar
                if (request()->has('filtro_titulo') && !empty(request()->get('filtro_titulo'))) {
                    $query->whereHas('titulacionPersona', function ($q) {
                        $q->where('id_titulacion', request()->get('filtro_titulo'));
                    });
                }

                // 6. Filtro por Estatus de Expediente
                if (request()->has('filtro_estatus') && !empty(request()->get('filtro_estatus'))) {
                    $query->whereHas('titulacionPersona', function ($q) {
                        $q->where('id_estatus_expediente', request()->get('filtro_estatus'));
                    });
                }

                // 7. Filtro por Estado de Nacimiento
                if (request()->has('filtro_estado') && !empty(request()->get('filtro_estado'))) {
                    $query->whereHas('lugarNacimiento.ciudad', function ($q) {
                        $q->where('id_estado', request()->get('filtro_estado'));
                    });
                }
            }, true)
            ->rawColumns(['empresa', 'titulo', 'action'])
            ->setRowId('id_personas');
    }

    public function query(Persona $model): EloquentBuilder
    {
        return $model->newQuery()
            ->with([
                'titulacionPersona.pnf', 
                'titulacionPersona.titulacion', 
                'empresaPersona.empresa',
                'cohorte' 
            ])
            ->selectRaw("*, CONCAT(primer_nombre_personas, ' ', primer_apellido_personas) as full_name");
    }

    protected function getTableId(): string
    {
        return 'personas-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder()
            ->parameters([
                'initComplete' => "function () {
                    this.api().columns().every(function () {
                        var column = this;
                    });
                }",
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('cedula_personas')->title('Cédula')->width(100),
            Column::make('full_name')->title('Nombres y Apellidos')->searchable(true),
            // Columna de cohorte eliminada de la vista de la tabla
            Column::make('empresa')->title('Empresa')->searchable(false)->orderable(false),
            Column::make('titulo')->title('Título a Optar')->searchable(false)->orderable(false),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(100)->addClass('text-center all'),
        ];
    }
}