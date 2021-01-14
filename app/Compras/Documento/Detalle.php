<?php

namespace App\Compras\Documento;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $table = 'compra_documento_detalles';
    public $timestamps = true;
    protected $fillable = [
        'documento_id',
        'articulo_id',
        'cantidad',
        'precio',
        'costo_flete'
    ];

    public function documento()
    {
        return $this->belongsTo('App\Compras\Documento\Documento');
    }
    public function articulo()
    {
        return $this->belongsTo('App\Compras\Articulo');
    }
}
