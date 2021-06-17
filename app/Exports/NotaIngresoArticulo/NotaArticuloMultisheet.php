<?php

namespace App\Exports\NotaIngresoArticulo;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NotaArticuloMultisheet implements WithMultipleSheets
{
    public function sheets(): array
    {
      return [
          "lista"=>new NotaArticuloExport(),
          "listCombobox"=>new NotaArticuloList()
      ];

    }
}
