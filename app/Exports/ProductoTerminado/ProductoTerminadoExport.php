<?php

namespace App\Exports\ProductoTerminado;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductoTerminadoExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $data = array();
        array_push($data,   [
            'producto' => 'ULTRAMAG POTE X 250 G. - MAQUILA',
            'articulo' => 'ACEI015 - ACEITE ESENCIAL DE MENTA.',
            'cantidad' => '10',
            'peso' => '2',
            'observacion' => 'Producto terminado '
        ]);
        return $data;
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
    public function title(): string{
        return "lista";
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


                $C_productos= DB::table('productos')->where('estado','ACTIVO')->get();
                $C_articulos =DB::table('articulos')->where('estado', 'ACTIVO')->get();

                $i=count($C_productos)+1;
                $k=count($C_articulos)+1;

                for ($j = 2; $j < 100; $j++) {

                    $validation = $event->sheet->getCell('A' . $j)->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(false);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Error en escritura');
                    $validation->setError('El valor no esta en la lista');
                    $validation->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_BETWEEN);
                    $validation->setFormula1('listCombobox!$A$2:$A$'.($i));


                    $validatione = $event->sheet->getCell('B' . $j)->getDataValidation();
                    $validatione->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validatione->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $validatione->setAllowBlank(false);
                    $validatione->setShowInputMessage(false);
                    $validatione->setShowErrorMessage(true);
                    $validatione->setShowDropDown(true);
                    $validatione->setErrorTitle('Error en escritura');
                    $validatione->setError('El valor no esta en la lista');
                    $validatione->setOperator(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::OPERATOR_BETWEEN);
                    $validatione->setFormula1('listCombobox!$B$2:$B$'.($k));
                }


                // $event->sheet->getColumnDimension('C')->setWidth(20);
            },
        ];
    }

}
