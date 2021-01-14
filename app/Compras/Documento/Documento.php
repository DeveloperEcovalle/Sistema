<?php

namespace App\Compras\Documento;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'compra_documentos';
    public $timestamps = true;
    protected $fillable = [
            'empresa_id',
            'modo_compra',
            'tipo_compra',
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
            'usuario_id',

        ];

    public function empresa()
    {
        return $this->belongsTo('App\Mantenimiento\Empresa\Empresa');
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
