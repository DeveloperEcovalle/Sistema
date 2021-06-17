<?php

namespace App\Exports\ProductoTerminado;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ErrorProductoTerminadoExport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents
{

    public $datos = array();
    public $errores = array();
    public $columna =   [
        'producto' => "A",
        'articulo' => "B",
        'cantidad' => "C",
        'peso' => "D",
        'observacion' => "E"
    ];
    public function __construct($datos, $errores)
    {
        $this->datos = $datos;
        $this->errores = $errores;

    }
    public function array(): array
    {
        return $this->datos;
    }
    public function headings(): array
    {
        return [
            [
                'producto',
                'articulo',
                'cantidad',
                'peso',
                'observacion'
            ]
        ];
    }
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => [self::class, 'beforeWriting'],
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:E1')->applyFromArray(
                    [
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                            'rotation' => 90,
                            'startColor' => [
                                'argb' => '1ab394',
                            ],
                            'endColor' => [
                                'argb' => '1ab394',
                            ],
                        ],
                    ]
                );
                for ($i = 0; $i < count($this->errores); $i++) {
                    $event->sheet->getStyle(($this->columna[$this->errores[$i]['atributo']]) . ($this->errores[$i]['fila']))->applyFromArray(
                        [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                    'color' => ['argb' => 'fc0303'],
                                ],
                            ]
                        ]
                    );
                }
                // $event->sheet->getColumnDimension('C')->setWidth(20);
            },
        ];
    }
}
