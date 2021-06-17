<?php

namespace App\Imports\ProductoTerminado;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;



class ProductoTerminadoImportMultiSheet implements withMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 =>new ImportsProductoTerminado()
        ];
    }

}
