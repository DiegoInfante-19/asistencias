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
                'dom'        => '<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-3"lB>f>rtip',
                'buttons'    => [
                    'dom' => [
                        'container' => ['className' => 'dt-buttons d-flex gap-2'],
                        'button'    => ['className' => 'btn']
                    ],
                    'buttons' => [
                        ['extend' => 'pdf', 'text'   => '<i class="bi bi-file-earmark-pdf me-1"></i> PDF', 'className' => 'btn btn-outline-secondary btn-tabla'],
                        ['extend' => 'excel', 'text' => '<i class="bi bi-file-earmark-excel me-1"></i> Excel', 'className' => 'btn btn-outline-secondary btn-tabla'],
                        ['extend' => 'print', 'text' => '<i class="bi bi-printer me-1"></i> Imprimir', 'className' => 'btn btn-outline-secondary btn-tabla'],
                        ['extend' => 'copy', 'text'  => '<i class="bi bi-clipboard me-1"></i> Copiar', 'className' => 'btn btn-outline-secondary btn-tabla']
                    ]
                ],
                'language'   => [
                    'processing'     => 'Procesando...',
                    'search'         => '<b>Buscar:</b>',
                    'lengthMenu'     => '<b>Mostrar _MENU_ registros</b>',
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
