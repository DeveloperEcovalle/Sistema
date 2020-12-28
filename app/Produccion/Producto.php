<?php

namespace App\Produccion;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'familia_id',
        'sub_familia_id',
        'presentacion',
        'stock',
        'stock_minimo',
        'precio_venta_minimo',
        'precio_venta_maximo',
        'igv',
        'estado'
    ];
    protected $casts = [
        'igv' => 'boolean'
    ];

    public function familia()
    {
        return $this->belongsTo('App\Produccion\Familia');
    }

    public function sub_familia()
    {
        return $this->belongsTo('App\Produccion\SubFamilia');
    }

    public function detalles()
    {
        return $this->hasMany('App\Produccion\ProductoDetalle');
    }

    public function getDescripcionCompleta()
    {
        return $this->codigo.' - '.$this->nombre;
    }
}
