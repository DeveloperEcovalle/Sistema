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
            'fecha_emision',
            'fecha_entrega',
            'moneda',
            'observacion',
            'igv',
            'igv_check',
            'estado',
            'tipo_cambio',
            'enviado',
            'usuario_id'
        ];

    public function empresa()
    {
        return $this->belongsTo('App\Mantenimiento\Empresa');
    }
    public function proveedor()
    {
        return $this->belongsTo('App\Compras\Proveedor');
    }
    public function usuario()
    {
        return $this->belongsTo('App\User','usuario_id');
    }
}
