<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ErrorExcel implements FromArray,ShouldAutoSize,WithHeadings,WithEvents
{
    public $datos=array();
    public $errores=array();
    public $columna=   ['codigo'=>"A",
    'nombre'=>"B",
    'descripcion'=>"C",
    'familia'=>"D",
    'subfamilia'=>"E",
    'medida'=>"F",
    'linea_comercial'=>"G",
    'codigo_barra'=>"H",
    'stock'=>"I",
    'stock_minimo'=>"J",
    'precio_venta_minimo'=>"K",
    'precio_venta_maximo'=>"L",
    'peso_producto'=>"M",
    'igv'=>"N",
    'codigo_lote'=>"O",
    'cantidad'=>"P",
    'fecha_vencimiento'=>"Q",
    'fecha_entrega'=>"R"
];
    public function __construct($datos,$errores)
    {
        $this->datos=$datos;
        $this->errores=$errores;

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->datos;
    }
    public function headings(): array
    {
        return [
            ['codigo',
            'nombre',
            'descripcion',
            'familia',
            'subfamilia',
            'medida',
            'linea_comercial',
            'codigo_barra',
            'stock',
            'stock_minimo',
            'precio_venta_minimo',
            'precio_venta_maximo',
            'peso_producto',
            'igv',
            'codigo_lote',
            'cantidad',
            'fecha_vencimiento',
            'fecha_entrega'
            ]
        ]
       ;
    }
    public function registerEvents(): array
    {
        return [

            BeforeWriting::class => [self::class, 'beforeWriting'],
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('O1:R1')->applyFromArray([


                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => '00bbd4',
                        ],
                        'endColor' => [
                            'argb' => '00bbd4',
                        ],
                    ],


                ]

                );
                $event->sheet->getStyle('A1:N1')->applyFromArray([


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
                for($i=0;$i<count($this->errores);$i++)
                {
                    $event->sheet->getStyle(($this->columna[$this->errores[$i]['atributo']]).($this->errores[$i]['fila']))->applyFromArray([


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

