<?php

namespace App\Ventas;

use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    protected $table = 'guias_remision';
    protected $fillable = [
        'documento_id',
        'cantidad_productos',
        'peso_productos',
        'tienda_id',
        'observacion',
        'ubigeo_llegada',
        'ubigeo_partida',
        'estado',
        'sunat',
        'correlativo',
        'serie',
        'ruta_comprobante_archivo',
        'nombre_comprobante_archivo',
        'dni_conductor',
        'placa_vehiculo'
    ];

    public function documento()
    {
        return $this->belongsTo('App\Ventas\Documento\Documento','documento_id');
    }

    public function tienda()
    {
        return $this->belongsTo('App\Ventas\Tienda','tienda_id');
    }
}
