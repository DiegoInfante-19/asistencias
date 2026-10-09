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
                    // Ahora usamos nuestro Accessor que respeta estrictamente el PNF
                    $tituloEspecifico = $persona->titulacionPersona->nombre_titulo_especifico;
                    $nombreMostrar = $tituloEspecifico ?? optional($persona->titulacionPersona->titulacion)->nombre_titulo_base ?? 'Título Desconocido';
                    
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
                // 1. Filtro por Cohorte
                if (request()->has('filtro_cohorte') && !empty(request()->get('filtro_cohorte'))) {
                    $query->where('id_cohortes', request()->get('filtro_cohorte'));
                }

                // 2. Filtro por Empresa (Blindado al trabajo más reciente)
                if (request()->has('filtro_empresa') && !empty(request()->get('filtro_empresa'))) {
                    $query->whereHas('empresaPersona', function ($q) {
                        $q->where('id_empresa', request()->get('filtro_empresa'))
                          ->whereRaw('id_empresa_personas = (select max(id_empresa_personas) from empresa_personas as ep where ep.id_personas = personas.id_personas)');
                    });
                }
                
                // 3. Filtro por Cargo (Blindado al trabajo más reciente)
                if (request()->has('filtro_cargo') && !empty(request()->get('filtro_cargo'))) {
                    $query->whereHas('empresaPersona', function ($q) {
                        $q->where('id_cargo', request()->get('filtro_cargo'))
                          ->whereRaw('id_empresa_personas = (select max(id_empresa_personas) from empresa_personas as ep where ep.id_personas = personas.id_personas)');
                    });
                }

                // 4. Filtro por PNF (Blindado al expediente más reciente de la persona)
                if (request()->has('filtro_pnf') && !empty(request()->get('filtro_pnf'))) {
                    $pnfId = request()->get('filtro_pnf');
                    $query->whereHas('titulacionPersonas', function ($q) use ($pnfId) {
                        $q->where('id_pnf', $pnfId)
                          ->whereRaw('id_titulacion_personas = (select max(id_titulacion_personas) from titulacion_personas as tp where tp.id_personas = personas.id_personas)');
                    });
                }

                // 5. Filtro por Título a Optar (Corregido y Simplificado)
                if (request()->has('filtro_titulo') && !empty(request()->get('filtro_titulo'))) {
                    $tituloId = request()->get('filtro_titulo');
                    $query->whereHas('titulacionPersonas', function ($q) use ($tituloId) {
                        // Filtramos directo por la llave foránea real sin hacer cruces peligrosos
                        $q->where('id_titulacion', $tituloId)
                          ->whereRaw('id_titulacion_personas = (select max(id_titulacion_personas) from titulacion_personas as tp where tp.id_personas = personas.id_personas)');
                    });
                }

                // 6. Filtro por Estatus de Expediente (Blindado al expediente más reciente de la persona)
                if (request()->has('filtro_estatus') && !empty(request()->get('filtro_estatus'))) {
                    $estatusId = request()->get('filtro_estatus');
                    $query->whereHas('titulacionPersonas', function ($q) use ($estatusId) {
                        $q->where('id_estatus_expediente', $estatusId)
                          ->whereRaw('id_titulacion_personas = (select max(id_titulacion_personas) from titulacion_personas as tp where tp.id_personas = personas.id_personas)');
                    });
                }

                // 7. Filtro por Estado de Nacimiento (Corregido para usar la relación anidada de ciudad)
                if (request()->has('filtro_estado') && !empty(request()->get('filtro_estado'))) {
                    // Usamos notación de punto para cruzar de lugarNacimiento -> ciudad
                    $query->whereHas('lugarNacimiento.ciudad', function ($q) {
                        $q->where('id_estado', request()->get('filtro_estado'));
                    });
                }

                // 8. Filtro por Profesor (Corrección: Uso de inscripcionActiva y profesores.id_profesor)
                if (request()->has('filtro_profesor') && !empty(request()->get('filtro_profesor'))) {
                    $profesorId = request()->get('filtro_profesor');
                    $query->whereHas('inscripcionActiva', function ($q) use ($profesorId) {
                        $q->whereHas('seccion.profesores', function ($subq) use ($profesorId) {
                            $subq->where('profesores.id_profesor', $profesorId);
                        });
                    });
                }

                // 9. Filtro por Sección
                if (request()->has('filtro_seccion') && !empty(request()->get('filtro_seccion'))) {
                    $seccionId = request()->get('filtro_seccion');
                    $query->whereHas('inscripcionActual', function ($q) use ($seccionId) {
                        $q->where('id_seccion', $seccionId);
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
            Column::make('empresa')->title('Empresa')->searchable(false)->orderable(false),
            Column::make('titulo')->title('Título a Optar')->searchable(false)->orderable(false),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(100)->addClass('text-center all'),
        ];
    }
}