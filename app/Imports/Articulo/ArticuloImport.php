<?php

namespace App\Imports\Articulo;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArticuloImport implements ToCollection, WithHeadingRow
{
    public $data = array();
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            array_push($this->data, array(
                'descripcion' => $row['descripcion'],
                'codigo_fabrica' => $row['codigo_fabrica'],
                'stock_min' => $row['stock_min'],
                'precio_compra' => $row['precio_compra'],
                'presentacion' => $row['presentacion'],
                'categoria' => $row['categoria'],
                'almacen' => $row['almacen'],
                'unidad_medida' => $row['unidad_medida'],
                'codigo_barra' => $row['codigo_barra']
            ));
        }
    }
    public function get_data()
    {
        return $this->data;
    }
}
