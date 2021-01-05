<?php

namespace App\Compras;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{

    protected $table = 'pagos';
    public $timestamps = true;
    protected $fillable = [
            'orden_id',
            'banco',
            'ruta_archivo',
            'nombre_archivo',
            'fecha_pago',
            'monto',
            'moneda',
            'tipo_cambio',
            'observacion',
            'estado'
        ];

    public function bancos()
    {
        return $this->belongsTo('App\Compras\Banco','banco');
    }

    public function orden()
    {
        return $this->belongsTo('App\Compras\Orden');
    }
    
}
