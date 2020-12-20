<?php

namespace App\Compras;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';
    public $timestamps = true;
    protected $fillable = [
            'empresa_id',
            'modo_compra',
            'proveedor_id',
            'fecha_documento',
            'fecha_entrega',
            'moneda',
            'observacion',
            'igv',
            'igv_check',
            'estado',
        ];

    public function empresa()
    {
        return $this->belongsTo('App\Mantenimiento\Empresa');
    }
    public function proveedor()
    {
        return $this->belongsTo('App\Compras\Proveedor');
    }
}
