<?php

namespace App\Ventas\Documento;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'cotizacion_documento';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'moneda',
        'fecha_documento',
        'fecha_atencion',
        'sub_total',
        'total_igv',
        'total',
        'total_exento',
        'user_id',
        'estado',
        'igv',
        'igv_check',
        'tipo_venta',
        'cotizacion_venta'
    ];

    public function empresa()
    {
        return $this->belongsTo('App\Mantenimiento\Empresa\Empresa', 'empresa_id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Ventas\Cliente', 'cliente_id');
    }

    public function detalles()
    {
        return $this->hasMany('App\Ventas\CotizacionDetalle');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
