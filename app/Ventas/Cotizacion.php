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
        'total',
        'sub_total',
        'igv',
        'monto',
        'monto_exento',
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
}
