<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AnaliticaExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $vista;
    protected $datos;
    protected $parametros;

    public function __construct($vista, $datos, $parametros = [])
    {
        // Mapeamos a vistas específicas para Excel si deseas separarlas, o usamos la misma
        $this->vista = $vista;
        $this->datos = $datos;
        $this->parametros = $parametros;
    }

    public function view(): View
    {
        return view($this->vista, [
            'datos' => $this->datos,
            'parametros' => $this->parametros
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Aplicar bordes finos a toda la tabla generada para garantizar que NUNCA falten bordes
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'CBD5E1'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
            },
        ];
    }
}