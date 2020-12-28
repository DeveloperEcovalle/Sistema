<?php

namespace App\Ventas;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'moneda',
        'fecha_documento',
        'fecha_atencion',
        'total_afecto',
        'sub_total',
        'total_igv',
        'total',
        'total_exento',
        'user_id',
        'estado'
    ];

    public function empresa()
    {
        return $this->belongsTo('App\Mantenimiento\Empresa', 'empresa_id');
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
