<?php

namespace App\DataTables;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB; // Añadido para el facade DB

class PersonasDataTable extends BaseDataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return EloquentDataTable::create($query)
            
            // Columna 1: Cédula (Ya viene directa del query)
            
            // Columna 2: Primer Nombre y Primer Apellido
            ->addColumn('full_name', function ($persona) {
                return $persona->primer_nombre_personas . ' ' . $persona->primer_apellido_personas;
            })

            // Columna 3: Empresa
            ->addColumn('empresa', function ($persona) {
                return $persona->empresaPersona && $persona->empresaPersona->empresa
                    ? $persona->empresaPersona->empresa->nombre_empresa
                    : '<span class="text-muted fst-italic">Sin empresa</span>';
            })

            // Columna 4: Título a Optar (ESPECÍFICO)
            ->addColumn('titulo', function ($persona) {
                // Verificamos que tenga expediente
                if ($persona->titulacionPersona && $persona->titulacionPersona->id_titulacion) {
                    
                    // Buscamos dinámicamente el nombre ESPECÍFICO en la tabla titulos_pnf
                    // cruzando el PNF actual del estudiante y su Título base.
                    $tituloEspecifico = DB::table('titulos_pnf')
                        ->where('id_pnf', $persona->titulacionPersona->id_pnf)
                        ->where('id_titulo', $persona->titulacionPersona->id_titulacion)
                        ->value('nombre_titulo_pnf');

                    // Usamos el título específico. Si por error en la BD no existe, hacemos un fallback seguro al base.
                    $nombreMostrar = $tituloEspecifico ?? $persona->titulacionPersona->titulacion->nombre_titulo_base ?? 'Título Desconocido';
                    
                    return $nombreMostrar;
                }
                
                return '<span class="text-muted fst-italic">No asignado</span>';
            })

            // Columna 5: Acciones
            ->addColumn('action', function ($persona) {
                return view('personas.partials.actions', compact('persona'))->render();
            })

            // Lógica UI: Asignar clase CSS a la fila según el sexo
            ->setRowClass(function ($persona) {
                if ($persona->sexo_personas === 'M') {
                    return 'bg-masculino';
                } elseif ($persona->sexo_personas === 'F') {
                    return 'bg-femenino';
                }
                return '';
            })
            
            // Permitimos que el HTML (spans en cursiva y botones) se renderice (Quitamos 'pnf' de aquí)
            ->rawColumns(['empresa', 'titulo', 'action'])
            ->setRowId('id_personas');
    }

    public function query(Persona $model): EloquentBuilder
    {
        // Eager Loading de las relaciones anidadas
        return $model->newQuery()
            ->with([
                'titulacionPersona.pnf', 
                'titulacionPersona.titulacion', 
                'empresaPersona.empresa'
            ])
            ->selectRaw("*, CONCAT(primer_nombre_personas, ' ', primer_apellido_personas) as full_name");
    }

    protected function getTableId(): string
    {
        return 'personas-table';
    }

    public function html(): HtmlBuilder
    {
        return $this->sharedHtmlBuilder();
    }

    protected function getColumns(): array
    {
        return [
            Column::make('cedula_personas')->title('Cédula')->width(100),
            Column::make('full_name')->title('Nombres y Apellidos')->searchable(true),
            Column::make('empresa')->title('Empresa')->searchable(false)->orderable(false),
            Column::make('titulo')->title('Título a Optar')->searchable(false)->orderable(false),
            // Eliminada la columna PNF de las cabeceras
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(100)->addClass('text-center all'),
        ];
    }
}