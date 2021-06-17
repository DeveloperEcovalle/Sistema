<?php

namespace App\Exports\Articulo;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ErrorArticuloExport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents
{
    public $datos = array();
    public $errores = array();
    public $columna =   [
        'descripcion' => "A",
        'codigo_fabrica' => "B",
        'stock_min' => "C",
        'precio_compra' => "D",
        'presentacion' => "E",
        'categoria' => "F",
        'almacen' => "G",
        'unidad_medida' => "H",
        'codigo_barra' => "I"
    ];
    public function __construct($datos, $errores)
    {
        $this->datos = $datos;
        $this->errores = $errores;
        Log::info($errores);
    }
    public function array(): array
    {
        return $this->datos;
    }
    public function headings(): array
    {
        return [
            [
                'descripcion',
                'codigo_fabrica',
                'stock_min',
                'precio_compra',
                'presentacion',
                'categoria',
                'almacen',
                'unidad_medida',
                'codigo_barra'
            ]
        ];
    }
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => [self::class, 'beforeWriting'],
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:I1')->applyFromArray(
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
