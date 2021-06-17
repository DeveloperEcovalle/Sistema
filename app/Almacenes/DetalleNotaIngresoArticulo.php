<?php

namespace App\Almacenes;

use Illuminate\Database\Eloquent\Model;

class DetalleNotaIngresoArticulo extends Model
{
    protected $table = 'detalle_nota_ingreso_articulo';
    protected $fillable = [
        'id',
        'nota_ingreso_articulo_id',
        'lote',
        'cantidad',
        'articulo_id',
        'fecha_vencimiento'
    ];
    public $timestamps = true;
}
