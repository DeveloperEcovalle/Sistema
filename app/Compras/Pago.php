<?php

namespace App\Compras;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{

    protected $table = 'pagos';
    public $timestamps = true;
    protected $fillable = [
            'banco_id',
            'ruta_archivo',
            'nombre_archivo',
            'fecha_pago',
            'monto',
            'moneda',
            'tipo_cambio',
            'observacion',
            'estado'
        ];

    public function banco()
    {
        return $this->belongsTo('App\Compras\Banco');
    }
    
}
