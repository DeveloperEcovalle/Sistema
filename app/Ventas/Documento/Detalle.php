<?php

namespace App\Ventas\Documento;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $table = 'cotizacion_documento_detalles';
    protected $fillable = [
        'cotizacion_id',
        'producto_id',
        'cantidad',
        'precio',
        'importe',
        'estado'
    ];

    public function cotizacion()
    {
        return $this->belongsTo('App\Ventas\Cotizacion');
    }

    public function producto()
    {
        return $this->belongsTo('App\Almacenes\Producto');
    }
}