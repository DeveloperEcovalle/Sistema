<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ModeloExport implements FromArray,WithHeadings,ShouldAutoSize,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $data=array();
        array_push($data,  ['codigo'=>'ACEI001-10',
        'nombre'=>'ACEITE ULTRAMAG FSC. PET X 55 ML.',
        'descripcion'=>'DESCRIPCION DEL PRODUCTO',
        'familia'=>'LÍNEA DE MINERALES Y CARBONES VEGETALES',
        'subfamilia'=>'ACEITES',
        'medida'=>'UNIDAD (BIENES)',
        'linea_comercial'=>'SISTEMA ÓSEO-ESTRECTURAL',
        'codigo_barra'=>'',
        'stock'=>'12',
        'stock_minimo'=>'10',
        'precio_venta_minimo'=>'40',
        'precio_venta_maximo'=>'50',
        'peso_producto'=>'0,09',
        'igv'=>'SI',
        'codigo_lote'=>'LOTEA',
        'cantidad'=>'12',
        'fecha_vencimiento'=>'2021-04-30',
        'fecha_entrega'=>'2021-04-16'
        ]);
        return $data;
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



               // $event->sheet->getColumnDimension('C')->setWidth(20);

            },
        ];
    }
}
