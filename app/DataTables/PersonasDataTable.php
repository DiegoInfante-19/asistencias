<?php

namespace App\DataTables;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Illuminate\Support\Facades\DB;

class PersonasDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            ->addColumn('full_name', function ($persona) {
                return $persona->nombre_completo;
            })
            ->addColumn('cohorte_nombre', function ($persona) {
                return $persona->cohorte ? $persona->cohorte->numero_cohorte ?? 'Sin Cohorte' : 'Sin Cohorte';
            })
            ->addColumn('empresa', function ($persona) {
                return $persona->empresaPersona && $persona->empresaPersona->empresa
                    ? $persona->empresaPersona->empresa->nombre_empresa
                    : '<span class="text-muted fst-italic">Sin empresa</span>';
            })
            ->addColumn('formacion_previa', function ($persona) {
                if ($persona->formacionAcademica->isEmpty()) {
                    return '<span class="text-muted fst-italic">Ninguna</span>';
                }
                return $persona->formacionAcademica->map(function($f) {
                    return '• ' . $f->nombre_completo_titulo;
                })->implode('<br>');
            })
            ->addColumn('titulo_pnf', function ($persona) {
                if ($persona->titulacionPersona) {
                    // CORRECCIÓN APLICADA: Priorizar la relación directa tituloPnf
                    $nombreMostrar = $persona->titulacionPersona->tituloPnf->nombre_titulo_pnf 
                        ?? $persona->titulacionPersona->nombre_titulo_especifico
                        ?? optional($persona->titulacionPersona->titulacion)->nombre_titulo_base;
                    
                    if ($nombreMostrar) {
                        return e($nombreMostrar);
                    }
                }
                return '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-dash-circle me-1"></i>No colocado todavía</span>';
            })
            ->addColumn('pnf_nombre', function ($persona) {
                return $persona->titulacionPersona && $persona->titulacionPersona->pnf
                    ? e($persona->titulacionPersona->pnf->nombre_pnf)
                    : '<span class="text-muted fst-italic">Sin PNF</span>';
            })
            ->addColumn('action', function ($persona) {
                return view('personas.partials.actions', compact('persona'))->render();
            })
            ->setRowClass(function ($persona) {
                if ($persona->sexo_personas === 'M') return 'bg-masculino';
                if ($persona->sexo_personas === 'F') return 'bg-femenino';
                return '';
            })
            // INTERCEPTOR DE BÚSQUEDA GLOBAL Y FILTROS
            ->filter(function ($query) {
                // 1. CAPTURA DE BÚSQUEDA GLOBAL
                $keyword = request()->input('search.value');
                if (!empty($keyword)) {
                    $query->where(function($q) use ($keyword) {
                        $q->where('primer_nombre_personas', 'like', "%{$keyword}%")
                          ->orWhere('segundo_nombre_personas', 'like', "%{$keyword}%")
                          ->orWhere('primer_apellido_personas', 'like', "%{$keyword}%")
                          ->orWhere('segundo_apellido_personas', 'like', "%{$keyword}%")
                          ->orWhere('email_personas', 'like', "%{$keyword}%")
                          ->orWhereRaw("CONCAT(primer_nombre_personas, ' ', primer_apellido_personas) like ?", ["%{$keyword}%"])
                          ->orWhereRaw("CONCAT(primer_nombre_personas, ' ', segundo_nombre_personas, ' ', primer_apellido_personas, ' ', segundo_apellido_personas) like ?", ["%{$keyword}%"]);
                    });
                }

                // 2. FILTROS PERSONALIZADOS (Selects de la cabecera)
                if (request()->has('filtro_cohorte') && !empty(request()->get('filtro_cohorte'))) {
                    $query->where('id_cohortes', request()->get('filtro_cohorte'));
                }
                if (request()->has('filtro_empresa') && !empty(request()->get('filtro_empresa'))) {
                    $query->whereHas('empresaPersona', function ($q) {
                        $q->where('id_empresa', request()->get('filtro_empresa'))
                          ->whereRaw('id_empresa_personas = (select max(id_empresa_personas) from empresa_personas as ep where ep.id_personas = personas.id_personas)');
                    });
                }
                if (request()->has('filtro_cargo') && !empty(request()->get('filtro_cargo'))) {
                    $query->whereHas('empresaPersona', function ($q) {
                        $q->where('id_cargo', request()->get('filtro_cargo'))
                          ->whereRaw('id_empresa_personas = (select max(id_empresa_personas) from empresa_personas as ep where ep.id_personas = personas.id_personas)');
                    });
                }
                if (request()->has('filtro_pnf') && !empty(request()->get('filtro_pnf'))) {
                    $pnfId = request()->get('filtro_pnf');
                    $query->whereHas('titulacionPersonas', function ($q) use ($pnfId) {
                        $q->where('id_pnf', $pnfId)
                          ->whereRaw('id_titulacion_personas = (select max(id_titulacion_personas) from titulacion_personas as tp where tp.id_personas = personas.id_personas)');
                    });
                }
                // CORRECCIÓN APLICADA: Búsqueda dual por id_titulos_pnf o id_titulacion
                if (request()->has('filtro_titulo') && !empty(request()->get('filtro_titulo'))) {
                    $tituloId = request()->get('filtro_titulo');
                    $query->whereHas('titulacionPersonas', function ($q) use ($tituloId) {
                        $q->where(function($sub) use ($tituloId) {
                            $sub->where('id_titulos_pnf', $tituloId)
                                ->orWhere('id_titulacion', $tituloId);
                        })->whereRaw('id_titulacion_personas = (select max(id_titulacion_personas) from titulacion_personas as tp where tp.id_personas = personas.id_personas)');
                    });
                }
                if (request()->has('filtro_estatus') && !empty(request()->get('filtro_estatus'))) {
                    $estatusId = request()->get('filtro_estatus');
                    $query->whereHas('titulacionPersonas', function ($q) use ($estatusId) {
                        $q->where('id_estatus_expediente', $estatusId)
                          ->whereRaw('id_titulacion_personas = (select max(id_titulacion_personas) from titulacion_personas as tp where tp.id_personas = personas.id_personas)');
                    });
                }
                if (request()->has('filtro_estado') && !empty(request()->get('filtro_estado'))) {
                    $query->whereHas('lugarNacimiento.ciudad', function ($q) {
                        $q->where('id_estado', request()->get('filtro_estado'));
                    });
                }
                if (request()->has('filtro_profesor') && !empty(request()->get('filtro_profesor'))) {
                    $profesorId = request()->get('filtro_profesor');
                    $query->whereHas('inscripcionActiva', function ($q) use ($profesorId) {
                        $q->whereHas('seccion.profesores', function ($subq) use ($profesorId) {
                            $subq->where('profesores.id_profesor', $profesorId);
                        });
                    });
                }
                if (request()->has('filtro_seccion') && !empty(request()->get('filtro_seccion'))) {
                    $seccionId = request()->get('filtro_seccion');
                    $query->whereHas('inscripcionActual', function ($q) use ($seccionId) {
                        $q->where('id_seccion', $seccionId);
                    });
                }
            })
            ->rawColumns(['empresa', 'titulo_pnf', 'formacion_previa', 'action'])
            ->setRowId('id_personas');
    }

    public function query(Persona $model): EloquentBuilder
    {
        return $model->newQuery()
            ->with([
                'titulacionPersona.pnf', 
                'titulacionPersona.titulacion', 
                'titulacionPersona.tituloPnf', 
                'empresaPersona.empresa',
                'cohorte', 
                'formacionAcademica.titulo',
                'formacionAcademica.tituloPnf'
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
            ->setTableId('personas-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel')->addClass('btn btn-success btn-sm shadow-sm'),
                Button::make('pdf')->text('<i class="bi bi-file-earmark-pdf"></i> PDF')->addClass('btn btn-danger btn-sm shadow-sm'),
                Button::make('print')->text('<i class="bi bi-printer"></i> Imprimir')->addClass('btn btn-secondary btn-sm shadow-sm'),
                Button::make('colvis')->text('<i class="bi bi-layout-three-columns me-1"></i> Columnas')->addClass('btn btn-outline-secondary btn-sm shadow-sm')
            ])
            ->addTableClass('table-sm align-middle')
            ->parameters([
                'responsive' => true,
                'autoWidth'  => false,
                'initComplete' => "function () {
                    $('#personas-table').css('font-size', '12px');
                    this.api().columns().every(function () {
                        var column = this;
                    });
                }",
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('full_name')->title('Nombres y Apellidos')->name('full_name'),
            Column::make('cedula_personas')->title('Cédula')->searchable(false),
            Column::make('email_personas')->title('Correo')->visible(false),
            Column::make('cohorte_nombre')->title('Cohorte')->name('cohorte_nombre')->visible(false)->searchable(false),
            Column::make('empresa')->title('Empresa')->name('empresa')->searchable(false),
            Column::make('formacion_previa')->title('Titulaciones Previas')->name('formacion_previa')->visible(false)->searchable(false),
            Column::make('titulo_pnf')->title('Título PNF a Optar')->name('titulo_pnf')->searchable(false),
            Column::make('pnf_nombre')->title('PNF')->name('pnf_nombre')->visible(false)->searchable(false),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(80)->addClass('text-center all'),
        ];
    }
}