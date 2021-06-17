<?php

namespace App\Exports\ProductoTerminado;


use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductoTerminadoList implements withEvents,ShouldAutoSize,WithTitle,HasReferencesToOtherSheets
{
    function title():String{
        return "listCombobox";
    }
    function  registerEvents(): array
    {
        return [
            BeforeWriting::class => [self::class, 'beforeWriting'],
            AfterSheet::class    => function (AfterSheet $event) {
                $C_productos = DB::table('productos')->where('estado', 'ACTIVO')->get();
                $C_articulos = DB::table('articulos')->where('estado', 'ACTIVO')->get();
               // $event->sheet->getColumnDimension('S')->setVisible(false);
                //$event->sheet->getColumnDimension('T')->setVisible(false);
                $event->sheet->setCellValue('A1','produtos');
                $event->sheet->setCellValue('B1' , 'articulos');
                $i = 2;
                foreach ($C_productos as $row) {

                    $event->sheet->setCellValue('A' . $i, $row->nombre);

                    $i++;
                }
                $k = 2;
                foreach ($C_articulos as $row) {
                    $event->sheet->setCellValue('B' . $k, $row->descripcion);
                    $k++;
                }

            }
        ];
    }
}
