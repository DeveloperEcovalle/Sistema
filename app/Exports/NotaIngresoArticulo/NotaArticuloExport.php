<?php

namespace App\Exports\NotaIngresoArticulo;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Mantenimiento\Tabla\General;
use App\Compras\Articulo;
class NotaArticuloExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents,WithTitle,HasReferencesToOtherSheets
{
 /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $data = array();
        array_push($data,   [
            'lote' => 'LOTEA',
            'articulo' => 'ACEITE ESENCIAL DE MENTA.',
            'cantidad' => '20',
            'fecha_vencimiento' => '2021-04-30'
        ]);
        return $data;
    }
    public function title(): string{
        return "lista";
    }
    public function headings(): array
    {
        return [
            [
                'lote',
                'articulo',
                'cantidad',
                'fecha_vencimiento'
            ]
        ];
    }
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => [self::class, 'beforeWriting'],
            AfterSheet::class    => function (AfterSheet $event) {

                $event->sheet->getStyle('A1:D1')->applyFromArray(
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

                $c_articulos = Articulo::where('estado', 'ACTIVO')->get();

                 $l_articulos=count($c_articulos)+1;
                // Log::info(implode(",", $medidas));
                for ($j = 2; $j < 100; $j++) {
                    $validation = $event->sheet->getCell('B' . $j)->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(false);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Error en escritura');
                    $validation->setError('El valor no esta en la lista');
                    $validation->setFormula1('listCombobox!$A$2:$A$'.($l_articulos));

                }


                // $event->sheet->getColumnDimension('C')->setWidth(20);
            },
        ];
    }
}
