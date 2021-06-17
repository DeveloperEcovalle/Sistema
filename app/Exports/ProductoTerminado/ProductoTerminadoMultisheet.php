<?php

namespace App\Exports\ProductoTerminado;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductoTerminadoMultisheet implements withMultipleSheets
{
    public function sheets(): array
    {
        return [
            "lista"=>new ProductoTerminadoExport(),
            "listCombobox"=>new ProductoTerminadoList()
        ];
    }
}
