<?php

namespace App\Exports\NotaIngresoArticulo;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Mantenimiento\Tabla\General;
use App\Compras\Articulo;
class NotaArticuloList implements WithEvents,ShouldAutoSize,WithTitle,HasReferencesToOtherSheets
{
    function title():String{
        return "listCombobox";
    }
    function  registerEvents(): array
    {
        return [
            BeforeWriting::class => [self::class, 'beforeWriting'],
            AfterSheet::class    => function (AfterSheet $event) {
                $c_origenes =  General::find(28)->detalles;
                $c_destinos =  General::find(30)->detalles;
                $c_articulos = Articulo::where('estado', 'ACTIVO')->get();
                $event->sheet->setCellValue('A1' , 'articulos');
                $k = 2;
                foreach ($c_articulos as $row) {
                    $event->sheet->setCellValue('A' . $k,$row->descripcion);
                    $k++;
                }

            }
        ];
    }
}
