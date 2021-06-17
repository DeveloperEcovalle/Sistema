<?php

namespace App\Exports\Articulo;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ArticuloExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents,WithTitle,HasReferencesToOtherSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $data = array();
        array_push($data,   [
            'descripcion' => 'ROMERO DESHIDRATADO.',
            'codigo_fabrica' => 'HOJA031',
            'stock_min' => '1',
            'precio_compra' => '1.00',
            'presentacion' => 'BOLSA DE PLASTICO',
            'categoria' => 'HOJA Y TALLO',
            'almacen' => 'MATERIA PRIMA CONVENCIONAL',
            'unidad_medida' => 'KGM-KILOGRAMO',
            'codigo_barra' => ''
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
                $C_almacen = DB::table('almacenes')->where('estado', 'ACTIVO')->get();
                $C_categorias = DB::table('categorias')->where('estado', 'ACTIVO')->get();
                $C_medidas = DB::table('tabladetalles')->where([['tabla_id', '=', '13']])->get();
                 $l_almacen=count($C_almacen)+1;
                 $l_categorias=count($C_categorias)+1;
                 $l_medidas=count($C_medidas)+1;
                // Log::info(implode(",", $medidas));
                for ($j = 2; $j < 100; $j++) {
                    $validation = $event->sheet->getCell('G' . $j)->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(false);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Error en escritura');
                    $validation->setError('El valor no esta en la lista');
                    $validation->setFormula1('listCombobox!$A$2:$A$'.($l_almacen));

                    $validation = $event->sheet->getCell('F' . $j)->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(false);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Error en escritura');
                    $validation->setError('El valor no esta en la lista');
                    $validation->setFormula1('listCombobox!$B$2:$B$'.($l_categorias));

                    $validation = $event->sheet->getCell('H' . $j)->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(false);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Error en escritura');
                    $validation->setError('El valor no esta en la lista');
                    $validation->setFormula1('listCombobox!$C$2:$C$'.($l_medidas));
                }


                // $event->sheet->getColumnDimension('C')->setWidth(20);
            },
        ];
    }
}
