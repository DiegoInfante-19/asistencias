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
            ->rawColumns(['empresa', 'titulo', 'action'])
            ->setRowId('id_personas');
    }

    public function query(Persona $model): EloquentBuilder
    {
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
            Column::computed('action')->title('Acciones')->exportable(false)->printable(false)->width(100)->addClass('text-center all'),
        ];
    }
}