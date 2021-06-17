<?php

namespace App\Imports\ProductoTerminado;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductoTerminadoImport implements ToCollection, WithHeadingRow
{
    public $data= array();
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            array_push($this->data, array(
                'producto' => $row['producto'],
                'articulo' => $row['articulo'],
                'cantidad' => $row['cantidad'],
                'peso' => $row['peso'],
                'observacion' => $row['observacion']
            ));
        }
    }
    public function get_data()
    {
        return $this->data;
    }
}
