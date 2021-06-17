<?php

namespace App\Imports\Articulo;

use App\Imports\Articulo\ImportsArticulo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ArticuloImportMultiSheet implements withMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
        return [
            0 =>new ImportsArticulo()
        ];
    }
}
