<?php

namespace App\Almacenes;

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
        'estado',
        'codigo_barra',
        'moneda',
        'linea_comercial',
    ];
    protected $casts = [
        'igv' => 'boolean'
    ];

    public function familia()
    {
        return $this->belongsTo('App\Almacenes\Familia');
    }

    public function sub_familia()
    {
        return $this->belongsTo('App\Almacenes\SubFamilia');
    }

    public function detalles()
    {
        return $this->hasMany('App\Almacenes\ProductoDetalle');
    }

    public function getDescripcionCompleta()
    {
        return $this->codigo.' - '.$this->nombre;
    }
}
