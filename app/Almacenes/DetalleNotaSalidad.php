<?php

namespace App\Almacenes;

use Illuminate\Database\Eloquent\Model;

class DetalleNotaSalidad extends Model
{
    protected $table = 'detalle_nota_salidad';
    protected $fillable = [
        'id',
        'nota_salidad_id',
        'lote_id',
        'cantidad',
        'producto_id'
    ];
    public $timestamps = true;
}
