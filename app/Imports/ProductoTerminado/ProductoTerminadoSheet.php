<?php

namespace App\Imports\ProductoTerminado;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductoTerminadoSheet implements WithMultipleSheets
{
    public $objeto;
    public function sheets(): array
    {
        $this->objeto = new ProductoTerminadoImport();
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
