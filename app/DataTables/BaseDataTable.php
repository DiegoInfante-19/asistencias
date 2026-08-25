<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

abstract class BaseDataTable extends DataTable
{

    abstract protected function getTableId(): string;

    protected function sharedHtmlBuilder(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId($this->getTableId())
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->parameters([
                'responsive' => true,
                'autoWidth'  => false,
                
                // 1. DOM ACTUALIZADO: 
                // - Primera línea: 'l' y 'f' (Registros y Buscador)
                // - Segunda línea: 'B' (Botones con separación gap-2)
                // - Tabla: 'rt'
                // - Pie: 'i' y 'p'
                'dom'        => '<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-2"lf><"d-flex flex-wrap gap-2 mb-3"B>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3"ip>',
                
                // 2. BOTONES INDIVIDUALES CON COLORES:
                'buttons'    => [
                    ['extend' => 'pdf',   'text' => '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',     'className' => 'btn btn-danger  shadow-sm'],
                    ['extend' => 'excel', 'text' => '<i class="bi bi-file-earmark-excel me-1"></i> Excel', 'className' => 'btn btn-success shadow-sm'],
                    ['extend' => 'print', 'text' => '<i class="bi bi-printer me-1"></i> Imprimir',         'className' => 'btn btn-secondary  shadow-sm'],
                    ['extend' => 'copy',  'text' => '<i class="bi bi-clipboard me-1"></i> Copiar',         'className' => 'btn btn-secondary shadow-sm']
                ],
                
                'language'   => [
                    'processing'     => 'Procesando...',
                    'search'         => '<h5>Buscar:</h5>',
                    'lengthMenu'     => '<h5>Mostrar _MENU_ registros</h5>',
                    'info'           => 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                    'infoEmpty'      => 'Mostrando registros del 0 al 0 de un total de 0 registros',
                    'infoFiltered'   => '(filtrado de un total de _MAX_ registros)',
                    'loadingRecords' => 'Cargando registros...',
                    'zeroRecords'    => 'No se encontraron resultados',
                    'emptyTable'     => 'Ningún dato disponible en esta tabla',
                    'paginate' => [
                        'first'    => '<i class="bi bi-chevron-double-left"></i>',
                        'previous' => '<i class="bi bi-chevron-left"></i> Anterior',
                        'next'     => 'Siguiente <i class="bi bi-chevron-right"></i>',
                        'last'     => '<i class="bi bi-chevron-double-right"></i>'
                    ]
                ]
            ]);
    }
}