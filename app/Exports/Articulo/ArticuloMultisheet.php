<?php

namespace App\Exports\Articulo;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ArticuloMultisheet implements WithMultipleSheets
{
  public function sheets(): array
  {
    return [
        "lista"=>new ArticuloExport(),
        "listCombobox"=>new ArticuloList()
    ];

  }
}
