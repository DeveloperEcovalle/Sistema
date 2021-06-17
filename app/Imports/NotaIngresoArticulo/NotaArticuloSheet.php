<?php

namespace App\Imports\NotaIngresoArticulo;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class NotaArticuloSheet implements WithMultipleSheets
{
    public $objeto;
    public function sheets(): array
    {
        $this->objeto = new NotaArticuloImport();
        return
            [
                0 => $this->objeto
            ];
    }
    public function get_data()
    {
        return $this->objeto->get_data();
    }
}
