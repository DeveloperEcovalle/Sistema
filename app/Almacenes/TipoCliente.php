<?php

namespace App\Almacenes;

use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    protected $table = 'productos_clientes';
    protected $fillable = [
        'cliente',
        'monto',
        'producto_id',
        'estado'
    ];

    public function producto()
    {
        return $this->belongsTo('App\Almacenes\Producto');
    }
}