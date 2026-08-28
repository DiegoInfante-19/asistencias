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
                
                // DOM REESTRUCTURADO: 
                // l = Length (Mostrar X registros), f = Filter (Buscador)
                // B = Buttons (PDF, Excel, Imprimir, Copiar)
                // rt = Table & Processing
                // i = Info (Mostrando registros...) , p = Pagination (Paginación a la derecha)
                'dom' => '<"row mb-3 align-items-center"<"col-md-6"l><"col-md-6 text-md-end text-start mt-2 mt-md-0"f>>' .
                         '<"row mb-3"<"col-12"B>>' .
                         'rt' .
                         '<"row mt-3 align-items-center"<"col-md-5 text-muted small"i><"col-md-7 d-flex justify-content-md-end justify-content-center"p>>',                
                
                'buttons'    => [
                    ['extend' => 'pdf',   'text' => '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',    'className' => 'btn btn-danger  shadow-sm btn-sm'],
                    ['extend' => 'excel', 'text' => '<i class="bi bi-file-earmark-excel me-1"></i> Excel', 'className' => 'btn btn-success shadow-sm btn-sm'],
                    ['extend' => 'print', 'text' => '<i class="bi bi-printer me-1"></i> Imprimir',         'className' => 'btn btn-secondary  shadow-sm btn-sm'],
                    ['extend' => 'copy',  'text' => '<i class="bi bi-clipboard me-1"></i> Copiar',         'className' => 'btn btn-secondary shadow-sm btn-sm']
                ],
                
                'language'   => [
                    'processing'     => 'Procesando...',
                    'search'         => 'Buscar:',
                    'lengthMenu'     => 'Mostrar _MENU_ registros',
                    'info'           => 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
                    'infoEmpty'      => 'Mostrando registros del 0 al 0 de un total de 0 registros',
                    'infoFiltered'   => '(filtrado de un total de _MAX_ registros)',
                    'loadingRecords' => 'Cargando registros...',
                    'zeroRecords'    => 'No se encontraron resultados',
                    'emptyTable'     => 'Ningún dato disponible en esta tabla',
                    'paginate' => [
                        'first'    => '<i class="bi bi-chevron-double-left"></i>',
                        'previous' => '<i class="bi bi-chevron-left"></i>',
                        'next'     => '<i class="bi bi-chevron-right"></i>',
                        'last'     => '<i class="bi bi-chevron-double-right"></i>'
                    ]
                ]
            ]);
    }
}