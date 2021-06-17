<?php

namespace App\Imports\NotaIngresoArticulo;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NotaArticuloImport implements ToCollection, WithHeadingRow
{
    public $data = array();
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            array_push($this->data, array(
                'lote' => $row['lote'],
                'articulo' => $row['articulo'],
                'cantidad' => $row['cantidad'],
                'fecha_vencimiento' => $row['fecha_vencimiento']
            ));
        }
    }
    public function get_data()
    {
        return $this->data;
    }
}
