<?php

namespace App\Ventas;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'fecha_documento',
        'fecha_atencion',
        'sub_total',
        'total_igv',
        'total',
        'user_id',
        'estado',
        'igv',
        'igv_check'
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
