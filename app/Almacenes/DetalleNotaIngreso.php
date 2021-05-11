<?php

namespace App\Almacenes;

use Illuminate\Database\Eloquent\Model;

class DetalleNotaIngreso extends Model
{
    protected $table = 'detalle_nota_ingreso';
    protected $fillable = [
        'id',
        'nota_ingreso_id',
        'lote',
        'cantidad',
        'producto_id',
        'fecha_vencimiento'
    ];
    public $timestamps = true;
}
