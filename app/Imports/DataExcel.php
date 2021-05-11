<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataExcel implements ToCollection,WithHeadingRow
{
    public $data=array();
    /**
    * @param Collection $collection
    */

    public function collection(Collection $collection)
    {

        foreach ($collection as $row) {
            array_push($this->data,array(
                'codigo'=>$row['codigo'],
        'nombre'=>$row['nombre'],
        'descripcion'=>$row['descripcion'],
        'familia'=>$row['familia'],
        'subfamilia'=>$row['subfamilia'],
        'medida'=>$row['medida'],
        'linea_comercial'=>$row['linea_comercial'],
        'codigo_barra'=>$row['codigo_barra'],
        'stock'=>$row['stock'],
        'stock_minimo'=>$row['stock_minimo'],
        'precio_venta_minimo'=>$row['precio_venta_minimo'],
        'precio_venta_maximo'=>$row['precio_venta_maximo'],
        'peso_producto'=>$row['peso_producto'],
        'igv'=>$row['igv'],
        'codigo_lote'=>$row['codigo_lote'],
        'cantidad'=>$row['cantidad'],
        'fecha_vencimiento'=>$row['fecha_vencimiento'],
        'fecha_entrega'=>$row['fecha_entrega']

            ));
        }
    }
    public function get_data()
    {
        return $this->data;
    }
}
