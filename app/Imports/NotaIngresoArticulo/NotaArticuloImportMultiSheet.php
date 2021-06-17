<?php

namespace App\Imports\NotaIngresoArticulo;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NotaArticuloImportMultiSheet implements withMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
        return [
            0 =>new ImportsNotaArticulo()
        ];
    }
}
