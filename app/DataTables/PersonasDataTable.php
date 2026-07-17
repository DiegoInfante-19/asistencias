<?php

namespace App\DataTables;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

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
                // Usamos la relación empresaPersona() definida en Persona.php
                return $persona->empresaPersona && $persona->empresaPersona->empresa
                    ? $persona->empresaPersona->empresa->nombre_empresa ?? 'Empresa Registrada'
                    : '<span class="text-muted fst-italic">Sin empresa</span>';
            })

            // Columna 4: Título a Optar
            ->addColumn('titulo', function ($persona) {
                // Usamos la relación titulacionPersona() definida en Persona.php
                return $persona->titulacionPersona && $persona->titulacionPersona->titulacion
                    ? $persona->titulacionPersona->titulacion->nombre_titulo ?? 'Título Asignado'
                    : '<span class="text-muted fst-italic">No asignado</span>';
            })

            // Columna 5: PNF
            ->addColumn('pnf', function ($persona) {
                return $persona->titulacionPersona && $persona->titulacionPersona->pnf
                    ? $persona->titulacionPersona->pnf->nombre_pnf ?? 'PNF Asignado'
                    : '<span class="text-muted fst-italic">No asignado</span>';
            })

            // Columna 6: Acciones
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
            
            // Permitimos que el HTML (spans en cursiva y botones) se renderice
            ->rawColumns(['empresa', 'titulo', 'pnf', 'action'])
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
            Column::make('pnf')->title('PNF')->searchable(false)->orderable(false),
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(100)->addClass('text-center all'),
        ];
    }
}