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
                
                // DOM REESTRUCTURADO: Aislar la paginación 'p' en un flex independiente a la derecha
// Reemplaza la línea 'dom' actual por esta:
'dom' => '<"row mb-2"<"col-md-6"l><"col-md-6"f>>' .
         '<"row mb-3"<"col-12"B>>' .
         'rt' .
         '<"row mt-3 align-items-center"<"col-md-5"i><"col-md-7 d-flex justify-content-md-end justify-content-center"p>>',                
                'buttons'    => [
                    ['extend' => 'pdf',   'text' => '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',    'className' => 'btn btn-danger  shadow-sm'],
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