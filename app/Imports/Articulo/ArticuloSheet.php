<?php

namespace App\Imports\Articulo;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ArticuloSheet implements WithMultipleSheets
{
    public $objeto;
    public function sheets(): array
    {
        $this->objeto = new ArticuloImport();
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
