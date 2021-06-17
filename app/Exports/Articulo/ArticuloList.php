<?php

namespace App\Exports\Articulo;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;

class ArticuloList implements WithEvents,ShouldAutoSize,WithTitle,HasReferencesToOtherSheets
{
    function title():String{
        return "listCombobox";
    }
    function  registerEvents(): array
    {
        return [
            BeforeWriting::class => [self::class, 'beforeWriting'],
            AfterSheet::class    => function (AfterSheet $event) {
                $C_almacen = DB::table('almacenes')->where('estado', 'ACTIVO')->get();
                $C_categorias = DB::table('categorias')->where('estado', 'ACTIVO')->get();
                $C_medidas = DB::table('tabladetalles')->where([['tabla_id', '=', '13']])->get();

                $event->sheet->setCellValue('A1','almacen');
                $event->sheet->setCellValue('B1' , 'categorias');
                $event->sheet->setCellValue('C1' , 'medidas');
                $i = 2;
                foreach ($C_almacen as $row) {

                    $event->sheet->setCellValue('A' . $i, $row->descripcion);

                    $i++;
                }
                $k = 2;
                foreach (  $C_categorias as $row) {
                    $event->sheet->setCellValue('B' . $k, $row->descripcion);
                    $k++;
                }
                $k = 2;
                foreach ($C_medidas as $row) {
                    $event->sheet->setCellValue('C' . $k, $row->simbolo.'-'.$row->descripcion);
                    $k++;
                }

            }
        ];
    }
}
