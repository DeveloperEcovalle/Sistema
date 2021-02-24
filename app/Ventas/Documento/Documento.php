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

    public function tipoOperacion(): string
    {
        $venta = tipos_venta()->where('descripcion', $this->tipo_venta)->first();
        if (is_null($venta))
            return "-";
        else
            return $venta->operacion;
    }

    public function tipoDocumento(): string
    {
        $venta = tipos_venta()->where('descripcion', $this->tipo_venta)->first();
        if (is_null($venta))
            return "-";
        else
            return $venta->simbolo;
    }

    public function serie(): string
    {
        $venta = tipos_venta()->where('descripcion', $this->tipo_venta)->first();
        if (is_null($venta))
            return "-";
        else
            return $venta->parametro;
    }

    public function simboloMoneda(): string
    {
        $moneda = tipos_moneda()->where('id', $this->moneda)->first();
        if (is_null($moneda))
            return "-";
        else
            return $moneda->parametro;
    }
}
